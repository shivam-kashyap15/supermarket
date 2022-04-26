<?php

require 'autoload.php';

?>

<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        
        <title>Supermarket Checkout</title>
        
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">


		<!-- Latest compiled and minified CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

		<!-- jQuery library -->
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>


		<!-- Latest compiled JavaScript -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    </head>
    <body>

    	<?php
    		$items = Items::itemList();

    		$special_discounts = [
    			0 => "3 for 130",
    			1 => "2 for 45",
    			2 => "2 for 38; 3 for 50",
    			3 => "5 if purchased with an ‘A’",
    			4 => ""
    		]
    	?>


		<div class="container mt-3">
			<div class="row mt-3">
				<div class="col-md-12">
					<h2>Products Table</h2>
					
					<table class="table">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>SKU</th>
								<th>Price</th>
								<th>Special Discount</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($items as $key => $item) { ?>
							<tr>
								<td><?php echo $key+1; ?></td>
								<td><?php echo $item['sku']; ?></td>
								<td><?php echo $item['unit_price']; ?></td>
								<td><?php echo $special_discounts[$key]; ?></td>
								<td><input type="number" class="form-control" id="<?php echo $item['sku']; ?>" name="<?php echo $item['sku']; ?>" value="0"></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>

					<button class="btn btn-success float-end" onclick="getTotalCost()">Get Total Cost</button>
				</div>
			</div>
			<hr>
			<div class="row mt-3">
				<div class="col-md-4 offset-md-4">
					<h2>Total Cost: <span id="total"></span></h2>
				</div>
			</div>

		</div>

    </body>
</html>





<script>

function getTotalCost() {
	var a = $('#A').val();
	var b = $('#B').val();
	var c = $('#C').val();
	var d = $('#D').val();
	var e = $('#E').val();
	
	$('#total').html("");

	$.ajax({
      	type: 'post',
      	url: 'getCost.php',
      	dataType: 'json',
      	data: {a: a, b: b, c: c, d: d, e: e},

      	success: function (response){
	    	$('#total').html(response);    
      	}
    });
}

</script>




