<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-BR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>{$_pageTitle}</title>

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
    
    {include file=$_contentFile}

</body>
</html>