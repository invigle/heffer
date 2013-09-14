<?php
print 'Password is <b>';
print strlen($_GET['password']);
print ' </b>characters long<hr>';

print 'HASHED PW: ';
print hash('sha256', "kjwbgGRWwglrnwvbw242gf25g35thgwrWFEWDG25t2".$_GET['password']);
?>