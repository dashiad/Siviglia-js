(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "TypeSwitcher",
          "BLANK_IS_NULL": true,
          "ON": [
            {
              "FIELD": "ft",
              "IS": "String",
              "THEN": "TYPE1"
            },
            {
              "FIELD": "ft",
              "IS": "Object",
              "THEN": "TYPE2"
            }
          ],
          "ALLOWED_TYPES": {
            "TYPE1": {
              "TYPE": "String"
            },
            "TYPE2": {
              "TYPE": "Container",
              "BLANK_IS_NULL": true,
              "FIELDS": {
                "ft": {
                  "TYPE": "String",
                  "BLANK_IS_NULL": true
                },
                "f2": {
                  "TYPE": "String"
                }
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit


//codeEnd
//callbackEnd
})