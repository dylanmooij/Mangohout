
<head>
    <link rel="stylesheet" href="https://www.demeubelimporteur.nl/wp-content/plugins/woocommerce/packages/woocommerce-blocks/build/style.css?ver=4.0.0">
    <link rel="stylesheet" href="https://www.demeubelimporteur.nl/wp-content/themes/woodmart/style.min.css?ver=3.3.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <div class="header">
        <a href="https://mangohoutonline.nl">
            <img class="center" width="300px" height="94px" src="https://cdn.shopify.com/s/files/1/0474/0454/8262/files/mangohoutonline-logo-2_300x@2x.png?v=1603131937" alt="">
        </a>
    </div>

</head>
<style>
    .header{
        padding: 0 0 20px 40px;
        background: black
    }
    .breadcrumbs a{
        text-decoration: none;
    }
    .site-page{
        height: 300px;
        position: relative;
        background-size: cover;
        background-image: url("https://cdn.shopify.com/s/files/1/0474/0454/8262/files/slider_1800x600_1feded90-b9fd-4629-8c04-487305e9e93e_1800x.jpg?v=1601475023");
    }
    .site-page:before {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
    }
    .site-page .container{
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }
    .track-template{
        margin-top: 50px;
    }
    .track-template .vc_row.wpb_row.vc_row-fluid{
        display: flex;
    }
    .track-template .inputs{
        margin-bottom: 20px;
    }
    .footer{
        border-top: 1px solid #d7d7d7;
    }
</style>
<body>
    <div class="col-md-12 bg-dark site-page" >
        <div class="container">
            <header class="entry-header text-light">
                <h1 class="entry-title text-center text-light">Volg je bestelling</h1>								
                <div class="breadcrumbs text-center" xmlns:v="http://rdf.data-vocabulary.org/#">
                    <a class="text-light" href="https://mangohoutonline.nl" rel="v:url" property="v:title">Home</a> » <span class="current">Volg je bestelling</span>
                </div>											
            </header>
        </div>
    </div>
    <div class="container col-md-12">
        <div class="track-template">
        <?php
            if(isset($_GET['order']) && isset($_GET['email'])) {
                $ordernumber = $_GET['order'];
                $email = strtolower($_GET['email']);
                
                include 'track/api.php';
                $order = getOrder($ordernumber, $email);

                if(is_object($order)) {
                    $items = getOrderItems($order->id);
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        include 'track/post.php';
                        
                        //Reload, because of changes
                        $order = getOrder($ordernumber, $email);
                        $items = getOrderItems($order->id);
                    }
                    include 'track/found.php';
                } else {
                    include 'track/not-found.php';
                }
            } else {
                include 'track/default.php';
            }
            ?>
        </div>
    </div>
</body>
<footer>
    <div class="footer text-center">
            <p class="fs-6">2021 @mangohoutonline</p>
    </div>
</footer>
