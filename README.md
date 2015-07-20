# gallneukirchen-api
API's zur Landessonderausstellung 2015 in Gallneukirchen.

### TESTHOST
[http://dev.matthiasschaffer.com/gallneukirchen-api/]

### API-Methoden
+ Veranstaltungen [/ver]

    #### Veranstaltungen
        REQUEST: curl --request GET 'http://dev.matthiasschaffer.com/gallneukirchen-api/ver/'

        RESPONSE:
        {
            "1":{"datum":"20.07.2015 ","titel":"Yoga im Freien f\u00fcr Kinder","ort":"Veitsdorfer Weg"},
            "2":{"datum":"20.07.2015 ","titel":"Langsamlauftreff\/Nordic Walking","ort":"Parkplatz Gusenhalle"},
            ...
        }

[http://dev.matthiasschaffer.com/gallneukirchen-api/]: http://dev.matthiasschaffer.com/gallneukirchen-api/
[/ver]: http://dev.matthiasschaffer.com/gallneukirchen-api/ver/
