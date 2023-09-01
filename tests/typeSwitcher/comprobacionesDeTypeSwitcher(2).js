(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "ALLOWED_TYPES": {
            "TIPO3": {
              "TYPE": "Container",
              "FIELDS": {
                "sf3": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "sf4": {
                  "TYPE": "Integer"
                }
              }
            },
            "TIPO4": {
              "TYPE": "Container",
              "FIELDS": {
                "sf1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "sf2": {
                  "TYPE": "Integer"
                }
              }
            }
          },
          "DEFAULT": {
            "TYPE": "TIPO3",
            "sf3": "aaa",
            "sf4": 10
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );

  // Comprobamos que, en este punto, ya que t1.f3.TYPE=="TIPO3" (por el valor por defecto),
  // existen sf3 y sf4:
  var initStatus = (t1.f3.sf3 === "aaa");
  var thrown = false;
  try {
    var val = t1.f3.sf2;
  } catch (e) {
    thrown = true;
  }
  initStatus = initStatus && thrown;
  // Se cambia ahora el tipo del typeswitcher.Tienen que haber desaparecido los tipos anteriores,
  // y aparecer los nuevos campos, aunque esten a nulo.
  t1.f3.TYPE = "TIPO4";
  var secondStatus = (t1.f3.sf1 == null);
  thrown = false;
  try {
    val = t1.f3.sf3;
  } catch (e) {
    thrown = true;
  }
  secondStatus = secondStatus && thrown;
  // Se deshace el cambio.
  t1.f3.TYPE = "TIPO3";
  thrown = false;
  var thirdStatus = (t1.f3.sf3 == null);
  try {
    val = t1.f3.sf1;
  } catch (e) {
    thrown = true;
  }
  thirdStatus = thirdStatus && thrown;
  t1.destruct();
  return initStatus && secondStatus && thirdStatus && countListeners() == 0;

//codeEnd
//callbackEnd
})