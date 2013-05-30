<?php
function translateActivityTypeName($at)
    {
        switch($at){
            case "block":
                return "Bloque";
            case "route":
                return "Ruta";
            case "individual":
                return "Individual";
            case "time":
                return "Temporal";
            case "count":
                return "Contable";
        }
    }
    
function getActivityType_TypeName($typename){
    $typereg= explode("-",$typename);
    if(isset($typereg[0]))
        return translateActivityTypeName($typereg[0]);
   else
        return "Sin Tipo";
}

function connectToDb()
{
    if(APPLICATION_ENV == "development")
    {
        $con = mysql_connect("localhost","root","ipbroad305.ldt");
        if (!$con)
        {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("organizame", $con);
    }
    else
    {
        $services_json = json_decode(getenv("VCAP_SERVICES"),true);
        $mysql_config = $services_json["mysql-5.1"][0]["credentials"];
        $username = $mysql_config["username"];
        $password = $mysql_config["password"];
        $hostname = $mysql_config["hostname"];
        $port = $mysql_config["port"];
        $db = $mysql_config["name"];
        $con = mysql_connect($hostname,$username,$password);
        if (!$con)
        {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($db);
    }
    return $con;
}

function execQuery($query){
    $con = connectToDb();
    $q=mysql_query($query);
    if(!$q)
    {
        var_dump(mysql_error());
        var_dump($query);
        return false;
    }
    if(strpos($query,"SELECT"))
    {
        $result=Array();
        while($row = mysql_fetch_assoc($q))
        {
            array_push($result,$row);
        }
        mysql_close($con);
        return $result;
    }
    else
    {
        mysql_close($con);
        return $q;
    }
}

function getActivityType_RegisterName($typename){
    $typereg= explode("-",$typename);
    $type="";
    if(isset($typereg[1]))
        $type.=translateActivityTypeName($typereg[1]);
    else
        $type.="Sin registro";
    if(isset($typereg[2]))
        $type.="-".translateActivityTypeName($typereg[2]);
    return $type;
}

function gettodayactivities(){
    $result = execQuery("
                SELECT a.id, a.name, a.description, c.start, at.name atname, c.id as cid, c.status
                FROM cronogram as c, activity as a, activitytype as at
                WHERE c.day='".date("d-m-y")."' 
                      AND c.user_id=".Zend_Auth::getInstance()->getIdentity()->userId."
                      AND c.activity_id=a.id
                      AND a.activitytype_id=at.id
                      AND c.status<>'deleted'
                ORDER BY c.start
             ");
    $result2 = array();
    foreach($result as $r)
    {
        $r['atname']=getActivityType_TypeName($r['atname'])." / ".getActivityType_RegisterName($r['atname']);
        array_push($result2,$r);            
    }
    return $result2;
}

function getblockactivities($id){
    $query = execQuery("
            SELECT a.id, a.name, a.description, at.name atname, ad.eorder start
            FROM activity as a, activitytype as at, activitydependence as ad 
            WHERE a.activitytype_id=at.id
                AND ad.dependent_activity_id=".$id."
                AND a.id=ad.independent_activity_id
            ORDER BY ad.eorder
         ");
    return $query;
}

function getrouteactivities($id)
{
    $query = execQuery("
            SELECT p.id, p.description, CONCAT(CONCAT(p.address,' '),p.second_address) address
            FROM placedependence as pd, place as p
            WHERE pd.activity_id=".$id." 
                AND p.id=pd.place_id
            ORDER BY pd.eorder
         ");
    return $query;
}

function getSelectHours(){
    $html="";
    for($i=0;$i<24;$i++)
        $html.="<option value=".$i." label=".$i.">".$i."</option>";
    return $html;
}

function getSelectMinutes(){
    $html="";
    for($i=0;$i<60;$i++)
        $html.="<option value=".$i." label=".$i.">".$i."</option>";
    return $html;
}

function measureStatus($cid,$id,$start){
    $query=execQuery("
            SELECT m.status, m.start, m.end
            FROM measure as m, cronogram as c 
            WHERE m.cronogram_id=c.id 
                AND m.activity_id=".$id." 
                AND c.id=".$cid."
                AND c.start='".$start."'");
    if(count($query)>0)
    {
        return array(
            'status'=>$query[0]['status'],
            'start'=>date("H:i:s",$query[0]['start']),
            'end'=>date("H:i:s",$query[0]['end']),
            'time'=>($query[0]['end']-$query[0]['start'])/60
            );
        
    }
    else
        return false;
}

function getCount($id)
{
    $query=execQuery("
            SELECT end
            FROM measure
            WHERE activity_id=".$id."
    ");
    return count($query);
}

function getEstimated($id)
{
    $query=execQuery("
            SELECT AVG((end-start)/60) as est
            FROM measure
            WHERE activity_id=".$id."
    ");
    if(count($query)>0)
    {
        if($query[0]['est']===null)
            return 0;
        else
            return round($query[0]['est'],1);
    }
    else
        return 0;
}

function getVariance($id)
{
    $query=execQuery("
            SELECT VARIANCE((end-start)/60) as var
            FROM measure
            WHERE activity_id=".$id."
    ");
    if(count($query)>0)
    {
        if($query[0]['var']===null)
            return 0;
        else
            return round($query[0]['var'],1);
    }
    else
        return 0;
}