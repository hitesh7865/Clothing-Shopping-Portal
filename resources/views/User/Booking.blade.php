<html>
    <head>
        <title>Forget Password Email</title>
    </head>
    <body>
        <table>
            <tr><td>Hello {{ $ud }},</td></tr>
            <tr><td>&nbsp</td></tr>
            <tr><td>Your Order has been successfully placed.<br>
            </td></tr>
            <tr><td>&nbsp</td></tr>
            <tr><td>&nbsp</td></tr>
            <tr>
				<th class="product-th">Product</th>
				<th class="quy-th">Quantity</th>
				<th class="total-th">Photo</th>
                <th class="total-th">Price</th>
			</tr>
            @foreach($billdata as $d)
            <tr>
                <td>{{ $d->Product_name}}</td>
                <td>{{ $d->qty }}</td>  
                <td><img src="{{ asset('uploads/productimage/'.$d->photo) }}" height="200" width="150" alt="image"></td>
                <td>{{ $d->Price }}</td> 
            </tr>
            @endforeach
            <tr><td>&nbsp</td></tr>
            <tr><td>Thanks & Regards,</td></tr>
            <tr><td>Clothing Shopping Site</td></tr>
        </table>
    </body>
</html>