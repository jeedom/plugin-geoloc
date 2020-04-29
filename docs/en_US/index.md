Plugin to manage coordinates and distance calculation
between 2 points, the journey time (by car) between 2 points as well as
the distance.

Setup 
=============

Once the plugin is installed and activated from the Market, you access the
page of the Geolocation plugin by :

![geoloc28](../images/geoloc28.jpg)

Here you find all your Geoloc equipment :

![geoloc29](../images/geoloc29.jpg)

> **Tip**
>
> As in many places on Jeedom, place the mouse on the far left
> brings up a quick access menu (you can
> from your profile always leave it visible).

Once an equipment is selected you get :

![geoloc screenshot1](../images/geoloc_screenshot1.JPG)

L'onglet « Général » permet de choisir le nom de l'équipement, l'objet
parent as well as its condition and visibility. L'onglet « Commande » permet
to add the information we want to get. Once
the added equipment, we have the choice between three types of controls
: fixed, dynamic and distance.

![geoloc screenshot2](../images/geoloc_screenshot2.jpg)

Fixed 
----

Represents a point with coordinates that do not change. By
example the coordinates of your home, your work ... It you
just note the coordinates in the form : Longitude latitude.

![geoloc3](../images/geoloc3.jpg)

To find the coordinates of your fixed position, simply go
sur Google map : <https://www.google.com / maps / preview> . If you
search for an address, then the coordinates will be in the URL address,
for example for 25 rue de Mogador :

![geoloc4](../images/geoloc4.jpg)

You can also left click on the map, and the
coordinates will appear in the small map at the top left.

![geoloc4](../images/geoloc4.jpg)

Dynamic 
---------

Represents a point with variable coordinates, the object moves.
This is usually your laptop. This command will therefore contain
last contact details sent until you send them
new. The URL to update this command is :

\#URL\_JEEDOM \# / core / api / jeeApi.php?api=\#API\_KEY\#&type=geoloc&id=\#ID\_CMD\#&value=%LOC

\#URL\_JEEDOM \# corresponds to your Jeedom access URL. It is (except
if you are connected to your local network) from the internet address that
you use to access Jeedom from outside. Do not forget
to indicate the port as well as / jeedom /.

api = \# API\_KEY \# corresponds to your API key, specific to your
installation. To find it you can either go to the plugin
Geoloc, it is indicated directly in the URL.

![geoloc5](../images/geoloc5.jpg)

Soit dans le menu « Général », puis « Administration » et «
Setup », en activant le mode Expert vous verrez alors une ligne
API key.

![geoloc6](../images/geoloc6.jpg)

&lt;id=\#ID\_CMD\# correspond à l'ID de votre commande. Once
you have given a name to your Geolocation order, determined
son type et sauvegardé, un numéro apparaît dans la case « \# » devant le
name your order.

![geoloc7](../images/geoloc7.jpg)

% LOC corresponds to your coordinates in the form Latitude, Longitude.

You must therefore perform an HTTP POST on this address by replacing
% LOC by your contact details.

Android example with Tasker 
=============

Be careful : for this example you need the Tasker app
for Android
(<https://play.google.com/store/apps/details?id = net.dinglisch.android.taskerm>).
Dans l'onglet « Tâches », nous rajoutons une nouvelle tâche ici appelée
« Jeedom ».

![geoloc8](../images/geoloc8.jpg)

Nous y ajoutons une première action, dans la catégorie « Divers », nous
sélectionnons « Obtenir la localisation ».

![geoloc9](../images/geoloc9.jpg)

![geoloc10](../images/geoloc10.jpg)

We will use any source to obtain our
position, and we're going to set a 30 sec delay so that Tasker has the
time to get our contact details.

![geoloc11](../images/geoloc11.jpg)

![geoloc12](../images/geoloc12.jpg)

Too short a time frame may not allow contact details to be obtained
or imprecise coordinates. The same is true of the type of
source. Nous ajoutons une deuxième action, dans la partie « Réseau »
cette fois, nous sélectionnons « Post HTTP ».

![geoloc13](../images/geoloc13.jpg)

![geoloc14](../images/geoloc14.jpg)

Dans la case « Serveur :Port » nous copions notre URL complétée sauf
for the% LOC part.

![geoloc15](../images/geoloc15.jpg)

![geoloc16](../images/geoloc16.jpg)

Lorsque nous lançons notre tâche « Jeedom », une icône devrait vous
inform about the use of your GPS in your notification bar.

![geoloc17](../images/geoloc17.jpg)

Once le délai écoulé, nous cliquons sur « tester » dans Jeedom et
the coordinates of our cell phone then appear in the popup.
Tasker automatically replaced the% LOC variable with your contact details.

![geoloc18](../images/geoloc18.jpg)

Now you just need to create a scenario in Tasker which will launch
this task when you need it. For example every hour,
when you connect by wifi…

Distance, Travel Time and Travel Distance 
=============

Calculate the distance, the journey time (by car, using
from Google Maps) or the trip distance (by car, using
Google Maps) between two points. It is therefore necessary to have already
filled at least two orders. Here we have the fixed coordinates
of our house as well as the updated contact details of our
portable. So we can calculate the distance between the two. We
sélectionnons « Distance » en type et nos deux commandes précédentes
in options. Once saved, we use the test button
and the distance then appears in the popup. Here 1.34 km.

![geoloc19](../images/geoloc19.jpg)

Likewise if you want the journey time or the distance of a journey, it
must choose respectively in type : "Travel time "or" Distance
path".

This plugin works as a module, i.e. once
saved we can find it in the list of actions or
commands, so it's very easy to use when creating
scenarios for example. We can for example carry out a scenario which
based on the distance between our laptop and the house for example.

Example scenario 
=============

Dans la partie « Scénario », nous créons un scénario nommé « Géoloc TV »
which allows us to turn on the TV when we are less than 250 m from
our house. Be careful : positioning systems not being
accurate to the nearest meter, remember to take a margin when creating
your scenarios. Dans « Mode de scénario » nous choisissons « Provoqué »
et en « Déclencheur » nous ajoutons notre portable. Indeed, it is
when the coordinates of our cellphone will be updated that
we will trigger the scenario.

![geoloc20](../images/geoloc20.jpg)

Nous ajoutons un élément « Si / Alors / Sinon » avec comme condition une
distance less than 250 m and as action the powering up of the
TV.

![geoloc21](../images/geoloc21.jpg)

Nous n'avons rien mis dans la partie « Sinon » ainsi il ne se passera
nothing if I am more than 250 m away. Once saved, we can
look at the log. We see here that Jeedom has tested the distance between the
portable and home and like this is more than 250 m, then there
nothing happened.

![geoloc22](../images/geoloc22.jpg)

For our test we check that the TV is off, the
widget displays 0 watt consumption.

![geoloc23](../images/geoloc23.jpg)

We get closer to our house and we start the task on
Tasker. We can see by testing the distance that it is
0.03 km now. So we are well under 250 m.

![geoloc24](../images/geoloc24.jpg)

The scenario part informs us that it has been launched
recently.

![geoloc25](../images/geoloc25.jpg)

A tour of the log allows us to see that it has been launched
following the update of the mobile's coordinates, and that the distance
was much less than 0.25 km.

![geoloc26](../images/geoloc26.jpg)

The TV plugin on the home screen shows that it is
now powered.

![geoloc27](../images/geoloc27.jpg)

Here is an example of using the Geolocation plugin.

Of course we realized the HTTP POST from a smartphone under
Android but it is entirely conceivable that a tablet could
do the same thing (with internet) or even a laptop with a
script to retrieve and send contact details.
