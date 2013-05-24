<?php
include(APPLICATION_PATH.'/../public/Functions.php');
class Dtad_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
    protected $_username;
    protected $_password;
    protected $_em;

    public function __construct($username, $password)
    {
        $this->_em = Zend_Registry::get('doctrine')->getEntityManager();
        $this->_username = $username;
        $this->_password = $password;
    }

    public function authenticate()
    {
        $user = execQuery("
                    SELECT u.id,u.username,u.email,u.salt,u.password,r.name
                    FROM user as u, role as r
                    WHERE u.username='".$this->_username."' AND r.id=u.role_id
                 ");
        if (!empty($user[0]))
        {
            $user=$user[0];
            $salt = $user['salt'];
            if (sha1($salt.$this->_password) == $user['password'])
            {
                $identity = (object)array('userId' => $user['id'],
                                          'username' => $user['username'],
                                          'role' => $user['name'],
                                          'email' => $user['email']
                                          );

                return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $identity);
            }
        }

        return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->_username);
    }
}
