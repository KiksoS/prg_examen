<?php
declare(strict_types=1);


// Este es el metodo por el cual vamos a obtener datos del usuario
$category = $_POST['category'];



// Esta funcion su funcion es pasandole por parametros el asociativo 'category' del metodo POST y pasarle un string que sea una opcion del estado aparte
// su funcionamiento es de retornar un atributo de html que hace que cuando tu marques una casilla se quede marcada cuando se vuelva a cargar la pagina
// esta ternaria seria muy raro que diese false porque no esta hecha para eso pero si pasara no retonaria nada
function isChecked(string $category, string $option): string
{
	return ($category == $option) ? "checked='checked'" : "";
}


// esta funcion recoge los datos del array creado por importFile() y el paramatro asociativo lo que hace es crear un nuevo array que este se rellenara de forma de que
// si la categoria es 0 no pasara nada y se rellenara el array entero identico al importFile() pero si $category es diferente a 0 se rellenara en base a el numero que tenga $category
// y la asociacion de 'category' dentro del array $data este se basara a su vez de nuestra base de datos y retorna nuestro array modificado
function filterData(array $data,string $category) :array {
   $arrMod =[];
   for ($i=0; $i < count($data); $i++) { 

      if ($category == 0) {
         $arrMod[] = [
            'image' => $data[$i]['image'],
            'id' => $data[$i]['id'],
            'product' => $data[$i]['product'],
            'price'=> $data[$i]['price'],
            'category'=> $data[$i]['category'],
         ];
      }elseif ($category == $data[$i]['category']) {
         $arrMod[] = [
            'image' => $data[$i]['image'],
            'id' => $data[$i]['id'],
            'product' => $data[$i]['product'],
            'price'=> $data[$i]['price'],
            'category'=> $data[$i]['category'],
         ];
      }
   }
   return $arrMod;
}

// getCategoryName coje el $category de $_POST como las anteriores veces pero ahora sera para canviar el subtitulo depende de la seccion a la que queremos acceder
// para evitar un infiero de if's lo que he hecho es usar un switch que pasandole este parametro retorna un titulo

function getCategoryName(string $category) :string {

   switch ($category) {
      case 0:
         return "All";
         break;
      
      case 1:
         return "Unisex";
         break;
      
      case 2:
         return "Men";
         break;
      case 3:
         return "Women";
         break;  
      default:
         return "No encontrado";
         break;
   }
}


// importFile coje por parametro el fichero.csv y lo que hace es relacionar cada columna de este fichero con una palabra asociada en un array bidireccional retorna este mismo array

function importFile(string $file): array {
   $handle = fopen($file, "r");
   $output = [];
   while ($line = fgetcsv($handle)) {
      $output[] = [
         'image' => $line[0],
         'id' => $line[1],
         'product' => $line[2],
         'price'=> $line[3],
         'category'=> $line[4]
       ]; 
   }
   fclose($handle);
   return $output;
}

//la funcion principal de drawProductList es imprimir lo que le pases en este caso le pasaremos como array la funcion filterData porque asi lo queremos luego tenemos una llamada de funcion
// dentro de esta funcion para poder imprimir nuestro titulo con la funcion getCategoryName() y tambien le pasamos nuestro parametro de $_POST 
function drawProductList (array $data,callable $fun,string $value) :void {
   $x=0;
   print "<div class='carousel-inner'>";

   // Tenemos un for que sirve para imprimir todas las paginas necesarias estas seran las 110 lineas de nuestro fichero .csv / 3 el tres sera porque en cada pagina queremos imprimir tres objetos
   // de nuestro array
   for ($i=1 ; $i <= round((count($data) / 3)) ; $i++ ) {

      //Aqui tenemos una ternaria que lo que hace principalmente es revisar si el primer valor impreso es active ya que lo necesitamos ( No se para que pero ahi esta )
      print "<div class='carousel-item".(($i == 1) ? ' active' :"")."'>";
      print "<div class='container'>";
      print "<h1 class='fashion_taital'>".$fun($value) ." Fashion (".$i.")</h1>";// esta linea llamamos a nuestro funcion del titulo y el $i es para nombrar las paginas      
      print "<div class='fashion_section_2'>";
      print "<div class='row'>";
       
      // Luego tenemos nuestro segundo for que lo que hara es imprimir esos tres objetos que queremos sacar del array, como queremos imprimir los tres siguientes cada vez nuestra condicion es distinta
      // esta lo que hace es iniciarse en 0 al principio y cual entra al bucle y da las 3 vueltas el valor de $j pasa a les el mismo de $x por lo cual cuando vuelva a ese bucle $j valdra lo ultimo que valio
      // $x lo cual asi acumulamos 
      for ($j= $x; $j <= ($x+2); $j++) {
         
         // Aqui comprobamos si la linea en $data existe porque si no lo hacemos daria un error al imprimir por lo cual si no hay nada en esa linea que no haga nada y eso lo logramos con el isset()
         if (isset($data[$j])) {
            print "<div class='col-lg-4 col-sm-4'>";
            print "<div class='box_main'>";
            print"<h4 class='shirt_text'>".$data[$j]['product'] ."(Men)</h4>";
            print "<p class='price_text'>Price <span style='color: #262626;'>€".$data[$j]['price'] ."</span></p>";
            print "<div class='electronic_img'><img src='./images/fashion/".$data[$j]['image']."'></div>";
            print "<div class='btn_main'>";
            print "<div class='buy_bt'><a href='#'>Buy Now</a></div>";
            print "<div class='seemore_bt'><span>Prod id: ".$data[$j]['id']."</span></div>";           
            print "</div></div></div>";
         }
         
      }
      $x= $j;
      print "</div></div></div></div>";
   }
   print "</div>";
}


// aqui le pasamos el valor del array importFile() a $data es mas facil manejar una variable que una funcion
$data = importFile("product.csv");
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <!-- basic -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- mobile metas -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <!-- site metas -->
   <title>Eflyer Fashion</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <!-- bootstrap css -->
   <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
   <!-- style css -->
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <!-- Responsive-->
   <link rel="stylesheet" href="css/responsive.css">
   <!-- fevicon -->
   <link rel="icon" href="images/fevicon.png" type="image/gif" />
   <!-- Scrollbar Custom CSS -->
   <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
   <!-- Tweaks for older IEs-->
   <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   <!-- fonts -->
   <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
   <!-- font awesome -->
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <!--  -->
   <!-- owl stylesheets -->
   <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Poppins:400,700&display=swap&subset=latin-ext" rel="stylesheet">
   <link rel="stylesheet" href="css/owl.carousel.min.css">
   <link rel="stylesoeet" href="css/owl.theme.default.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
</head>

<body>
   <!-- banner bg main start -->
   <div class="banner_bg_main">
      <!-- header top section start -->
      <div class="container">
         <div class="header_section_top">
            <div class="row">
               <div class="col-sm-12">
                  <div class="custom_menu">
                     <ul>
                        <li><a href="#">Best Sellers</a></li>
                        <li><a href="#">Gift Ideas</a></li>
                        <li><a href="#">New Releases</a></li>
                        <li><a href="#">Today's Deals</a></li>
                        <li><a href="#">Customer Service</a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- header top section start -->
      <!-- logo section start -->
      <div class="logo_section">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="logo"><a href="index.html"><img src="images/logo.png"></a></div>
               </div>
            </div>
         </div>
      </div>
      <!-- logo section end -->
      <!-- header section start -->
      <div class="header_section">
         <div class="container">
            <form action="" method="post">
               <div class="containt_main">
                  <div id="mySidenav" class="sidenav">
                     <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"></a>
                     <a href="index.html">Home</a>
                     <a href="fashion.html">Fashion</a>
                     <a href="electronic.html">Electronic</a>
                     <a href="jewellery.html">Jewellery</a>
                  </div>
                  <div class="dropdown">
                     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Category
                     </button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <!-- Aqui llamamos la funcion checked para la funcion que hemos nombrado antes -->
                        <span class="dropdown-item"><input type="radio" name="category" value="0" <?=isChecked($category,"0")?>>All</span>
                        <span class="dropdown-item"><input type="radio" name="category" value="1" <?=isChecked($category,"1")?>> Unisex</span>
                        <span class="dropdown-item"><input type="radio" name="category" value="2" <?=isChecked($category,"2")?>> Men</span>
                        <span class="dropdown-item"><input type="radio" name="category" value="3" <?=isChecked($category,"3")?>> Women</span>
                     </div>
                  </div>
                  <div class="main">
                     <!-- Another variation with a button -->
                     <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search this blog" name="criteria">
                        <div class="input-group-append">
                           <button class="btn btn-secondary" type="submit"
                              style="background-color: #f26522; border-color:#f26522 ">
                              <i class="fa fa-search"></i>
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="header_box">
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- header section end -->
      <!-- banner section start -->
      <div class="banner_section layout_padding">
         <div class="container">
            <div id="my_slider" class="carousel slide" data-ride="carousel">
               <div class="carousel-inner">
                  <div class="carousel-item">
                     <div class="row">
                        <div class="col-sm-12">
                           <h1 class="banner_taital">Get Start <br>Your favriot shoping</h1>
                           <div class="buynow_bt"><a href="#">Buy Now</a></div>
                        </div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="row">
                        <div class="col-sm-12">
                           <h1 class="banner_taital">Get Start <br>Your favriot shoping</h1>
                           <div class="buynow_bt"><a href="#">Buy Now</a></div>
                        </div>
                     </div>
                  </div>
                  <div class="carousel-item active">
                     <div class="row">
                        <div class="col-sm-12">
                           <h1 class="banner_taital">Get Start <br>Your favriot shoping</h1>
                           <div class="buynow_bt"><a href="#">Buy Now</a></div>
                        </div>
                     </div>
                  </div>
               </div>
               <a class="carousel-control-prev" href="#my_slider" role="button" data-slide="prev">
                  <i class="fa fa-angle-left"></i>
               </a>
               <a class="carousel-control-next" href="#my_slider" role="button" data-slide="next">
                  <i class="fa fa-angle-right"></i>
               </a>
            </div>
         </div>
      </div>
      <!-- banner section end -->
   </div>
   <!-- banner bg main end -->
   <!-- electronic section start -->
   <div class="fashion_section">
      <div id="electronic_main_slider" class="carousel slide" data-ride="carousel">
         <?php drawProductList(filterData($data,$category),'getCategoryName',$category);?> <!-- Esta es nuestra funcion de imprimir que hace lo mismo que hemos mencionado antes -->
         <a class="carousel-control-prev" href="#electronic_main_slider" role="button" data-slide="prev">
            <i class="fa fa-angle-left"></i>
         </a>
         <a class="carousel-control-next" href="#electronic_main_slider" role="button" data-slide="next">
            <i class="fa fa-angle-right"></i>
         </a>
      </div>
   </div>
   <!-- electronic section end -->
   <!-- footer section start -->
   <div class="footer_section layout_padding">
      <div class="container">
         <div class="footer_logo"><a href="index.html"><img src="images/footer-logo.png"></a></div>
         <div class="input_bt">
            <input type="text" class="mail_bt" placeholder="Your Email" name="Your Email">
            <span class="subscribe_bt" id="basic-addon2"><a href="#">Subscribe</a></span>
         </div>
         <div class="footer_menu">
            <ul>
               <li><a href="#">Best Sellers</a></li>
               <li><a href="#">Gift Ideas</a></li>
               <li><a href="#">New Releases</a></li>
               <li><a href="#">Today's Deals</a></li>
               <li><a href="#">Customer Service</a></li>
            </ul>
         </div>
         <div class="location_main">Help Line Number : <a href="#">+1 1800 1200 1200</a></div>
      </div>
   </div>
   <!-- footer section end -->
   <!-- copyright section start -->
   <div class="copyright_section">
      <div class="container">
         <p class="copyright_text">© 2020 All Rights Reserved. Design by <a href="https://html.design/">Free html
               Templates</a></p>
      </div>
   </div>
   <!-- copyright section end -->
   <!-- Javascript files-->
   <script src="js/jquery.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.bundle.min.js"></script>
   <script src="js/jquery-3.0.0.min.js"></script>
   <script src="js/plugin.js"></script>
   <!-- sidebar -->
   <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
   <script src="js/custom.js"></script>
   <script>
      function openNav() {
         document.getElementById("mySidenav").style.width = "250px";
      }

      function closeNav() {
         document.getElementById("mySidenav").style.width = "0";
      }
   </script>


</body>

</html>