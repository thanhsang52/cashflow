<?php
namespace App\Helpers;

class LDAP
{
    public $conn, $config;

    public function __construct()
    {
        $this->config = [
        'host' => env('LDAP_HOST', 'host'),
        'base_dn' => env('LDAP_BASE_DN', 'dc=domain,dc=com'),
        'account_suffix' => env('LDAP_ACCOUNT_SUFFIX', '@medicare.vn'),
        'port' => env('LDAP_PORT', 3268)
        ];
    }
    public function auth($uname, $pwd)
    {
        $this->conn = ldap_connect($this->config['host'], $this->config['port']);
        $escapedAdminUname = ldap_escape(env('LDAP_USERNAME'), null, LDAP_ESCAPE_DN);
        $escapedAdminPwd = ldap_escape(env('LDAP_PASSWORD'), null, LDAP_ESCAPE_DN);
        ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        try{
            //if (ldap_bind($this->conn, $bindDn, $escapedAdminPwd)) {
            if (@ldap_bind($this->conn, $uname, $pwd)) {
                return true;
            }else {
                $filter = '(&(objectClass=user))';
                $trimmerdUsername = trim(preg_replace('/[^a-zA-Z0-9\-\_@\.]/', '', $uname));
                $filter           = str_replace('%s', $trimmerdUsername, $filter);
                $output = shell_exec('ldapsearch -h "192.168.3.18" -p 3268 -U "'.$trimmerdUsername.'" -w "'.$pwd.'" -b "cn=Users,dc=NewMedicareVN,dc=local" "(objectClass=user)" -s base');
                if (! str_contains($output, "Success")) {
                    // The server returned no result-set.
                    return false;
                }
                return true;

                
            }
        }catch(Exception $e){
            return false;
        }
        return false;
    }
    

}