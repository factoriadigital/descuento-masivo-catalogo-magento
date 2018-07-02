# Script para descuento masivo del catálogo en Magento
![](https://img.shields.io/github/stars/factoriadigital/descuento-masivo-catalogo-magento.svg) ![](https://img.shields.io/github/forks/factoriadigital/descuento-masivo-catalogo-magento.svg) ![](https://img.shields.io/github/tag/factoriadigital/descuento-masivo-catalogo-magento.svg) ![](https://img.shields.io/github/release/factoriadigital/descuento-masivo-catalogo-magento.svg) ![](https://img.shields.io/github/issues/factoriadigital/descuento-masivo-catalogo-magento.svg) 

Este script aplica 

## Método de uso

Con el siguiente script podremos descontar un porcentaje de forma masiva a todo el catálogo en Magento 1.x

Para ello, podremos especificar el descuento mediante el parámetro --discount.

Para utilizarlo correctamente, deberíamos subir el archivo en el interior del directorio shell, localizado dentro de la raíz de la instalación de Magento. 
Una vez posicionado, accedemos mediante SSH a ese directorio y ejecutaremos el script con el parámetro especificado. Un ejemplo de uso para un 15% de descuento sería el siguiente:

```
cd <magento_root>/shell/
php catalog_discount.php --discount=15
```

Aparecerá un progreso, por lo que sabremos en todo momento que el script está funcionando.

Para aumentar la capacidad y sólo bajo nuestra responsabilidad y si el servidor tiene suficiente capacidad, dentro del script podremos modificar la variable

```protected $_pageSize = 500;```

La cual por defecto viene a 500, ya que internamente se cargan colecciones y productos en si, lo cual ralentiza las operaciones.

Adicionalmente, es posible que sea necesario especificar al principio del script los valores de PHP:

```
memory_limit
max_execution_time
```

Y aumentar su valor o bien dejarlos sin límites, por lo que quedarían de una forma similar a la siguiente:

```
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
```
