<?php if ($orders_iteration == 1) { ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td align="left" valign="top">
            <?php if ($store['config_logo']){ ?>
                <a href="<?php echo $order['store_url']; ?>">
                    <img alt="" src="<?php echo $store['config_logo']; ?>" />
                </a>
            <?php } ?>
            <?php if (!empty($store['config_title'])) { ?>
                <div>
                    <a href="<?php echo $order['store_url']; ?>"><b><?php echo $store['config_logo']; ?></b></a>
                </div>
            <?php } ?>
        </td>
        <td align="right" valign="top">
            <b style="font-size: 120%;"><?php if ($order['store_url']){ ?><a href="<?php echo $order['store_url']; ?>" style="text-decoration:none; color:#000000" target="_blank"><?php echo $store['config_name']; ?></a><?php } else { ?><?php echo $store['config_name']; ?><?php } ?></b>
            <?php if ($store['config_address']){ ?>
            <br /><?php echo $store['config_address']; ?>
            <?php } ?>
            <?php if ($store['config_telephone']){ ?>
            <br /><?php echo $store['config_telephone']; ?>
            <?php } ?>
            <?php if ($store['config_email']){ ?>
            <br /><a href="mailto:<?php echo $store['config_email']; ?>" style="text-decoration:none; color:#000000" target="_blank"><?php echo $store['config_email']; ?></a>
            <?php } ?>
        </td>
    </tr>
    </tbody>
</table>
<?php } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td height="8">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td width="50%">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            <tr>
                                <td width="90"><b><?php echo $text_date_added; ?></b></td>
                                <td><?php echo $order['date_added']; ?></td>
                            </tr>
                           
                            <tr>
                                <td width="90"><b><?php echo $text_invoice_no; ?></b></td>
                                <td><?php echo $order['invoice_prefix'] . $order['invoice_no']; ?></td>
                            </tr>
                           
                            <tr>
                                <td width="90"><b><?php echo $text_order_id; ?></b></td>
                                <td><?php echo $order['order_id']; ?></td>
                            </tr>
                            <?php if (!empty($order['custom_field'])) { ?>
                            <?php foreach ($order['custom_field'] as $custom_field) { ?>
                            <?php if ($custom_field['value']) { ?>
                            <tr>
                                <td width="90"><b><?php echo $custom_field['name']; ?>:</b></td>
                                <td><?php echo $custom_field['value']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td width="50%">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            <?php if ($order['order_status']) { ?>
                            <tr>
                                <td width="120"><b><?php echo $text_order_status; ?></b></td>
                                <td><?php echo $order['order_status']; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td width="120"><b><?php echo $text_payment_method; ?></b></td>
                                <td><?php echo $order['payment_method']; ?></td>
                            </tr>
                            <?php if ($order['shipping_address']) { ?>
                            <tr>
                                <td width="120"><b><?php echo $text_shipping_method; ?></b></td>
                                <td><?php echo $order['shipping_method']; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php if ($order['shipping_address'] || $order['payment_address']){ ?>
    <tr>
        <td colspan="2" height="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">
            <table border="0" cellpadding="5" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tbody>
                <tr>
                    <td bgcolor="<?php echo $config['module_pdf_invoice_color']; ?>" style="border:0.7px solid #969696;line-height:15pt;font-size:9pt;color:#ffffff;text-align:center;"<?php if ($order['shipping_address']){ ?> width="50%"<?php } ?>><b><?php echo $text_payment_address; ?></b></td>
                    <?php if ($order['shipping_address']){ ?>
                    <td bgcolor="<?php echo $config['module_pdf_invoice_color']; ?>" style="border:0.7px solid #969696;line-height: 15pt; font-size:9pt;color:#ffffff;text-align:center;" width="50%"><b><?php echo $text_shipping_address; ?></b></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td style="border:0.7px solid #969696;text-align:left;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td><?php echo $order['payment_address']; ?></td>
                            </tr>
                            <?php if (!empty($order['payment_custom_field'])) { ?>
                            <?php foreach ($order['payment_custom_field'] as $custom_field) { ?>
                            <?php if ($custom_field['value']) { ?>
                            <tr>
                                <td><b><?php echo $custom_field['name']; ?></b> <?php echo $custom_field['value']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </table>
                    </td>
                    <?php if ($order['shipping_address']){ ?>
                    <td width="50%" style="border:0.7px solid #969696;text-align:left;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td><?php echo $order['shipping_address']; ?></td>
                            </tr>
                            <?php if (!empty($order['shipping_custom_field'])) { ?>
                            <?php foreach ($order['shipping_custom_field'] as $custom_field) { ?>
                            <?php if ($custom_field['value']) { ?>
                            <tr>
                                <td><b><?php echo $custom_field['name']; ?></b> <?php echo $custom_field['value']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </table>
                    </td>
                    <?php } ?>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<br />
