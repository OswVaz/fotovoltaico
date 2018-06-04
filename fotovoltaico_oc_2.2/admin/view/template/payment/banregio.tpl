<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="box">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>

        </div>
    </div>
    <div class="container-fluid">
    <div class="content">




      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
      <input type = "hidden" name = "banregio_url_pruebas" value = "https://testhub.banregio.com/adq">
      <input type = "hidden" name = "banregio_url_produccion" value = "https://colecto.banregio.com/adq">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">

              <select name="banregio_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $banregio_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paymode"><?php echo $entry_banregio_text_config_one; ?></label>
            <div class="col-sm-10">
				<input type="text" name="banregio_Afiliacion" value="<?php echo $banregio_Afiliacion; ?>" size="10" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paymode"><?php echo $entry_banregio_text_config_two; ?></label>
            <div class="col-sm-10">
            <input type="text" name="banregio_ID_Medio" value="<?php echo $banregio_ID_Medio; ?>" size="10" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paymode"><?php echo $modo_operativo; ?></label>
            <div class="col-sm-10">
				    <select name="banregio_modo_operativo">
                <?php if ($banregio_modo_operativo) { ?>
                <option value="1" selected="selected">Producci&oacute;n</option>
                <option value="0">Pruebas</option>
                <?php } else { ?>
                <option value="1">Producci&oacute;n</option>
                <option value="0" selected="selected">Pruebas</option>
                <?php } ?>
              </select>
            </div>
          </div>

                    <div class="form-group">
            <label class="col-sm-2 control-label" ><?php echo $entry_status; ?></label>
            <div class="col-sm-10">

              <select name="banregio_status">
                <?php if ($banregio_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

        </form>
      </div>
    </div>



    </div>

	</div>
  </div>
</div>
<?php echo $footer; ?>
