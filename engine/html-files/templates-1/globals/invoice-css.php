<style type="text/css">

ul.amounts li{
	line-height:2;
}
.invoice table {
  margin:15px 0 15px;
}

.invoice .invoice-logo {
  margin-bottom:20px;
}

.invoice .invoice-logo .right,
.invoice .invoice-logo p {
  padding:5px 0;
  font-size:26px;
  line-height:28px;
  text-align:right;
}

.invoice .invoice-logo p span {
  display:block;
  font-size:14px;
}

.invoice .invoice-logo-space {
  margin-bottom:15px;
}

.invoice .invoice-payment strong {
  margin-right:5px;
}

.invoice .invoice-block {
  text-align:right;
}

.invoice .invoice-block .amounts {
  margin-top: 20px;
  font-size: 14px;
}
#invoice-container td{
	font-size: 14px;
}
#invoice-container .edit-invoice td{
	font-size: 15px;
}
#topcontrol{display:none;}

#invoice-container table tr.thead td,
tr.thead td,
.invoice table tr.thead th,
#invoice-container table tr.thead th,
.invoice table thead th,
#invoice-container table thead th{
	background:#C8F198;
}
#invoice-container table tr.total-row td{
	font-weight:bold;
}
#invoice-container table tr td.r{
	text-align:right;
}
.store-name{
	font-size:1.4em;
	text-shadow:1px 1px 1px #ddd;
}

.invoice.invoice-small .invoice-block .amounts{
  margin-top: 1px;
  font-size: 9px;
}
.invoice-small hr{
	margin:2px 0;
}

#invoice-container table,
#invoice-container table th,
#invoice-container table td,
#invoice-container table tr{
	border-color:#999999;
}
tr,
th,
td,
p,
li,
ul{
	-webkit-print-color-adjust: exact;
}
.invoice table.table-bordered thead tr th,
#invoice-container table.table-bordered thead tr th,
#invoice-container table.table-bordered tbody tr th,
.invoice table.table-bordered tbody tr th,
.invoice table.table-bordered tbody tr td,
#invoice-container table.table-bordered tbody tr td{
	border-color:#444;
}

body .invoice-small{
	font-family:calibri !important;
	/* font-family:arial !important; */
	color:#000 !important;
	font-size:9pt;
}
.invoice.invoice-small .invoice-block .amounts li,
#invoice-container .invoice-small .small-font li,
#invoice-container .invoice-small .small-font p,
#invoice-container .invoice-small .small-font{
	font-size:9pt;
	line-height:1.3;
}
.invoice.invoice-small .invoice-block .amounts li{
	line-height:1.7;
}
#invoice-container .invoice-small table th,
#invoice-container .invoice-small table td{
	font-size:9pt;
}
#invoice-container .invoice-small .smaller-font li,
#invoice-container .invoice-small .smaller-font p,
#invoice-container .invoice-small .smaller-font{
	font-size:9pt;
	line-height:1.6;
}
#invoice-container.invoice-small{
	margin-top:5px;
	margin-bottom:5px;
	padding-left:5px;
	padding-right:5px;
}
.invoice-small ul{
	margin-bottom:4px;
}
.invoice-small table thead > tr > th,
.invoice-small table tbody > tr > td{
	padding:3px;
}
.invoice-small table{
	margin:2px 0;
}
.store-name-small{
	font-weight:bold;
	line-height:1;
}
thead th,
.invoice-no-small,
.invoice-logo-small{
	text-align:center;
}
.invoice-small address{
	margin-bottom:5px;
}
td.r,
th.r{
	text-align:right;
}
table.table tr th{
	font-size:12px;
	font-weight:bold;
}
@media print{
	body,
	html,
	#invoice-container .invoice,
	#invoice-container{
		background-color:#fff;
	}
	
	.invoice table.table-bordered tr th,
	.invoice table.table-bordered tr td{
		padding:6px;
	}
}
#online_payment-form .form-check.form-check-inline{
	display:block;
}
</style>