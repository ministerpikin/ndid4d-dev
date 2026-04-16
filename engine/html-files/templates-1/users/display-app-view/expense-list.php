<tr class="<?php echo $class; ?> item-record" id="<?php echo $sval["id"]; ?>" data-firstname="<?php echo $sval["firstname"]; ?>" data-phone_number="<?php echo $sval["phone_number"]; ?>" data-email="<?php echo $sval["email"]; ?>" data-lastname="<?php echo $sval["lastname"]; ?>" data-staff_number="<?php echo $sval["ref_no"]; ?>" data-ref_no="<?php echo $sval["ref_no"]; ?>" data-role="<?php echo $sval["role"]; ?>">
  <td>
	<strong><?php echo ucwords( strtolower( $sval["firstname"]." ".$sval["lastname"] ) ); ?></strong><br />
	{<a href="tel:<?php echo $sval["phone_number"]; ?>"><?php echo $sval["phone_number"]; ?></a> | <a href="mailto:<?php echo $sval["email"]; ?>"><?php echo $sval["email"]; ?></a>}
  </td>
  <td>
	<?php echo ( ( isset( $sval["role"] ) && isset( $roles[ $sval["role"] ] ) )?$roles[ $sval["role"] ]:$sval["role"] ); ?><br />
  </td>
   
  <td class="r"><?php echo $sval["ref_no"]; ?></td>
</tr>