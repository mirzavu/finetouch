<?php
 $time = time();
 $result = date("Y-m-d (D) H:i:s",$time);
 echo "Current date and local time on server = $result <br>";
 $result = date("Y-m-d (D) H:i:s");
 echo "App date and time = $result <br>";
 ?>

<?php
//echo 'zone:'.date_default_timezone_get().'<br>';
echo 'date:'.date('Y-m-d H:i:s').'<br>';
echo 'test:'.strtotime(date('Y-m-d H:i:s')).":".date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
$paypal_iplist2 = gethostbynamel('notify.paypal.com');
echo var_dump($paypal_iplist2);
$paypal_iplist2 = gethostbynamel('www.google.co.nz');
echo var_dump($paypal_iplist2);

echo phpinfo();
?>
