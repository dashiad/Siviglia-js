(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "String",
          "SOURCE": {
            "TYPE": "Array",
            "LABEL": "Label",
            "VALUE": "Id",
            "DATA": [
              {
                "Label": "a",
                "Id": "a"
              },
              {
                "Label": "d",
                "Id": "d"
              }
            ]
          }
        },
        "f2": {
          "TYPE": "String",
          "SOURCE": {
            "TYPE": "Array",
            "LABEL": "Label",
            "VALUE": "Id",
            "DATA": [
              {
                "Label": "b",
                "Id": "b"
              },
              {
                "Label": "c",
                "Id": "c"
              }
            ]
          }
        },
        "f3": {
          "TYPE": "Integer",
          "SOURCE": {
            "TYPE": "Array",
            "LABEL": "[%Label%] [%SubLabel%]",
            "VALUE": "Id",
            "DATA": {
              "a": {
                "b": [
                  {
                    "Id": 1,
                    "Label": "Primero",
                    "SubLabel": "1º"
                  }
                ],
                "c": [
                  {
                    "Id": 2,
                    "Label": "Segundo",
                    "SubLabel": "2º"
                  }
                ]
              },
              "d": {
                "c": [
                  {
                    "Id": 3,
                    "Label": "Tercero",
                    "SubLabel": "3º"
                  },
                  {
                    "Id": 4,
                    "Label": "Cuarto",
                    "SubLabel": "4º"
                  }
                ]
              }
            },
            "PATH": "/{#../f1}/{#../f2}"
          }
        }
      }
    }
//definitionEnd

//codeInit

  var btype = new Siviglia.model.BaseTypedObject(def);
  // Primera prueba: Si el source no esta listo, deben saltar excepciones.
  var nChanges = 0;
  var nValids = 0;
  var curData = null;
  var nExcp = 0;
  var s = btype["*f3"].__getSource(btype);
  s.addListener("CHANGE", function (evType, data) {
    nChanges++;
    if (data.valid)
      nValids++;
    curData = data.value;
  });
  try {

    btype.f3 = 155;
    btype.save();

  } catch (e) {
    nExcp++;
  }
  // Aqui no puede haber aun valor.
  var status = (nExcp === 1 && nChanges === 1 && nValids === 0 && curData === null);

  // Cambiamos uno de los valores
  btype.f1 = "a";
  // Aun no se puede establecer el valor de f3
  try {

    btype.f3 = 155;
    btype.save();

  } catch (e) {
    nExcp++;
  }
  status = status && (nExcp === 2 && nChanges === 1 && nValids === 0 && curData === null);

  btype.f2 = "b";
  // En cuanto se establece el valor de f2, tiene que haberse cargado los datos del source de f3
  status = status && (nChanges === 2 && nValids === 1 && curData !== null && curData.length == 1 && curData[0].Id == 1);

  // No se puede establecer el valor de f3 a 2, solo a 1:
  try {

    btype.f3 = 155;
    btype.save();

  } catch (e) {
    nExcp++;
  }
  status = status && (nExcp == 3);

  btype.f3 = 1;
  btype.save();

  // Prueba dos : el fetch deberia devolver null, porque la combinacion f1=d y f2=b no esta soportada:
  // Ademas, f3 va a quedar ahora incorrecto, ya que el source ahora es null, por lo que no puede tener
  // el valor "1"

  btype.f1 = "d";

  status = status && btype["*f3"].__isErrored() == true && (nChanges === 3 && nValids === 1 && curData == null);


  // Se cambia f2 a "c":
  btype.f2 = "c";

  status = status && (nChanges === 4 && nValids === 2 && curData !== null && curData.length == 2 && curData[0].Id == 3);

  btype.destruct();
  status = status && countListeners() == 0;

  return status;


//codeEnd
//callbackEnd
})