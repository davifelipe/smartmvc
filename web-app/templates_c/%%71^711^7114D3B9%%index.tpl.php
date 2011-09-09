<?php /* Smarty version 2.6.26, created on 2011-09-08 01:58:00
         compiled from D:%5CVertrigoServ%5Cwww%5Cweb-app%5Cweb-app%5Cmodules%5Csmarty%5Cviews%5Cindex/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'D:\\VertrigoServ\\www\\web-app\\web-app\\modules\\smarty\\views\\index/index.tpl', 1, false),)), $this); ?>
<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>

<br />
Var de teste: <?php echo $this->_tpl_vars['var']; ?>