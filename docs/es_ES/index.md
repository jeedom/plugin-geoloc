

.

Configuración 
=============

Una vez que el complemento está instalado y activado desde Market, accede a
 :

![geoloc28](../images/geoloc28.jpg)

 :

![geoloc29](../images/geoloc29.jpg)

> **Punta**
>
> Como en muchos lugares de Jeedom, coloca el mouse en el extremo izquierdo
> abre un menú de acceso rápido (puedes
> desde tu perfil siempre déjalo visible).

Una vez que se selecciona un equipo, obtienes :

![geoloc screenshot1](../images/geoloc_screenshot1.JPG)

L'onglet « Général » permet de choisir le nom de l'équipement, l'objet
. L'onglet « Commande » permet
. 

: .

![geoloc screenshot2](../images/geoloc_screenshot2.jpg)

 
----



 : .

![geoloc3](../images/geoloc3.jpg)


 : <https:.> . Si usted
,
 :

![geoloc4](../images/geoloc4.jpg)


.

![geoloc4](../images/geoloc4.jpg)

 
---------

.
. 

 :

?api=\#API\_KEY\#&type=geoloc&id=\#ID\_CMD\#&value=%LOC

. 

. 
.



.

![geoloc5](../images/geoloc5.jpg)

Soit dans le menu « Général », puis « Administration » et «
Configuración », en activant le mode Expert vous verrez alors une ligne
Clave API.

![geoloc6](../images/geoloc6.jpg)

&lt;id=\#ID\_CMD\# correspond à l'ID de votre commande. 

son type et sauvegardé, un numéro apparaît dans la case « \# » devant le
.

![geoloc7](../images/geoloc7.jpg)

.


.

 
=============

Atención : 

:?.
Dans l'onglet « Tâches », nous rajoutons une nouvelle tâche ici appelée
« Jeedom ».

![geoloc8](../images/geoloc8.jpg)

Nous y ajoutons une première action, dans la catégorie « Divers », nous
sélectionnons « Obtenir la localisation ».

![geoloc9](../images/geoloc9.jpg)

![geoloc10](../images/geoloc10.jpg)



.

![geoloc11](../images/geoloc11.jpg)

![geoloc12](../images/geoloc12.jpg)


. 
source. Nous ajoutons une deuxième action, dans la partie « Réseau »
cette fois, nous sélectionnons « Post HTTP ».

![geoloc13](../images/geoloc13.jpg)

![geoloc14](../images/geoloc14.jpg)

Dans la case « Serveur :Port » nous copions notre URL complétée sauf
.

![geoloc15](../images/geoloc15.jpg)

![geoloc16](../images/geoloc16.jpg)

Lorsque nous lançons notre tâche « Jeedom », une icône devrait vous
.

![geoloc17](../images/geoloc17.jpg)

 le délai écoulé, nous cliquons sur « tester » dans Jeedom et
.
.

![geoloc18](../images/geoloc18.jpg)


. ,


 
=============



. 
. 


sélectionnons « Distance » en type et nos deux commandes précédentes
. 
. .

![geoloc19](../images/geoloc19.jpg)


 : "
".




. 
.

 
=============

Dans la partie « Scénario », nous créons un scénario nommé « Géoloc  »

. Atención : 

. Dans « Mode de scénario » nous choisissons « Provoqué »
et en « Déclencheur » nous ajoutons notre portable. 

.

![geoloc20](../images/geoloc20.jpg)

Nous ajoutons un élément « Si / Alors / Sinon » avec comme condition une

.

![geoloc21](../images/geoloc21.jpg)

Nous n'avons rien mis dans la partie « Sinon » ainsi il ne se passera
. 
. 

.

![geoloc22](../images/geoloc22.jpg)


.

![geoloc23](../images/geoloc23.jpg)



. .

![geoloc24](../images/geoloc24.jpg)


.

![geoloc25](../images/geoloc25.jpg)



.

![geoloc26](../images/geoloc26.jpg)


.

![geoloc27](../images/geoloc27.jpg)

.




.
