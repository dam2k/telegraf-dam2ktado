# telegraf-dam2ktado
Unofficial tado (tm) API exporter (telegraf execd input plugin) written in PHP.

Designed from scratch for myself, this telegraf execd input plugin makes use of [dam2k/tadoapi](https://github.com/dam2k/tadoapi) to get sensors and devices data from Tado network (Internet). Driven by telegraf STDIN signaling, exports a single json to be read by telegraf json2 internal parser to aggregate the tado metrics.
# Installation
The software requires that you first install PHP cli (8.2 is much better) and composer. It also needs a valid running instance of telegraf.
You can now install the telegraf-dam2ktado plugin.
- download this software
```
git clone 'https://github.com/dam2k/telegraf-dam2ktado.git'
```
- download software dependencies
```
cd telegraf-dam2ktado
composer update
cd ..
```
- move the telegraf-dam2ktado directory to your installation path (eg /opt/telegraf_dam2ktado)
```
mv telegraf-dam2ktado /opt/telegraf_dam2ktado
```
- setup the input plugin into telegraf
```
cp /opt/telegraf_dam2ktado/telegraf_dam2ktado.conf /etc/telegraf/telegraf.d
```
*If you choose another installation path (your mileage may vary), change the **command** path accordingly inside your config file.*
- edit the tado_telegraf_json2_plugin.php file, set your tado environment variable into the $tadoconf array: **tado.clientSecret, tado.username, tado.password, tado.homeid and eventually the statefile temporary file for the access token**

*You can obtain your environment data from my.tado.com/webapp/env.js after you logged into [my.tado.com](https://my.tado.com)*
- restart telegraf

```systemctl restart telegraf```
# Grafana
You may design a dashbord by yourself with the collected metrics described below, or, you can import and then personalize your dashboard from our **tado_telegraf_influxdb_grafana.json** file (or import the grafana dashboard **19301**).
# Tado metrics - tags and fields
The exported metrics collected by telegraf thanks to this plugin are defined below.
## Zones: your house rooms
### Tags
- **zoneid**: your tado defined zone ID
- **zonename**: your zone name (eg the room name, as you have defined the zone into your tado app)
- **zonetype**: your zone controller type (eg HEATING, AIR_CONDITIONING)
### Fields
- **power**: OFF or ON. If you enabled yor thermostat for the zone
- **desired_celsius**: Desired celsius temperature for the zone
- **linkstate**: ONLINE or OFFLINE. If the zone is detected online by the tado network
- **controltype**: MANUAL or AUTOMATIC. If the thermostat controller is setted manually or automatically
- **celsius**: The actual temperature for the zone in celsius degrees (C.)
- **humidity**: The actual humidity for the zone (percentual)
- **heating_power**: The actual heating power setted by tado to reach the desired room temperature (percentual)
## Devices: your tado devices
### Tags
- **zoneid**: your tado defined zone ID
- **zonename**: your zone name (eg the room name, as you have defined the zone into your tado app)
- **zonetype**: your zone controller type (eg HEATING, AIR_CONDITIONING)
- **serialNo**: your tado device serial number (thermostat, valve, etc)
### Fields
- **deviceType**: your tado device type (eg RU02, VA02, etc)
- **shortSerialNo**: short device serial number
- **currentFwVersion**: the device firmware version
- **connectionState**: the device connection state to the tado bridge. Can be true or false
- **batteryState**: the device battery level (NORMAL, LOW or n/a for devices without batteries)
- **mountingState**: the mounting state. CALIBRATED for devices that require mounting (like valves) or n/a if unnecessary

# Grafana screenshots
![Schermata del 2023-08-04 23-21-14](https://github.com/dam2k/telegraf-dam2ktado/assets/1271237/cdcebf1e-31f3-4e3c-8c6d-7e83f19ed4b9)
![Schermata del 2023-08-04 23-21-22](https://github.com/dam2k/telegraf-dam2ktado/assets/1271237/afa1e56b-7218-4701-9511-30d358830a66)
![Schermata del 2023-08-04 23-20-51](https://github.com/dam2k/telegraf-dam2ktado/assets/1271237/6d25b42f-0acb-46a3-b004-97243b21e8d8)
![Schermata del 2023-08-04 23-21-33](https://github.com/dam2k/telegraf-dam2ktado/assets/1271237/3e36e541-054c-484e-a947-4c2c9a288df6)
