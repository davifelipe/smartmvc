<?php /* Smarty version 2.6.26, created on 2011-09-08 01:58:00
         compiled from D:%5CVertrigoServ%5Cwww%5Cweb-app%5Cweb-app%5Clayout%5Csmarty.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-BR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php echo $this->_tpl_vars['_pageTitle']; ?>
</title>

</head>

<body>
    <h3>Módulo - Smarty</h3>
    <div id="menu">
        <ul>
            <li>
                <a href="?r=smarty/index">smarty/index</a>
            </li>
            <li>
                <a href="?r=index">index</a>
            </li>
            <li>
                 <a href="?r=index/viewphtml">index/viewphtml</a>
            </li>
        </ul>
    </div>
    
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['_contentFile'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</body>
</html>