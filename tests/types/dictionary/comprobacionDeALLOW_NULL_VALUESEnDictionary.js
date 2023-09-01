(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Dictionary",
          "ALLOW_NULL_VALUES": false,
          "SET_ON_EMPTY": true,
          "REQUIRED": true,
          "VALUETYPE": {
            "TYPE": "String"
          }
        },
        "f4": {
          "TYPE": "Dictionary",
          "ALLOW_NULL_VALUES": true,
          "SET_ON_EMPTY": false,
          "REQUIRED": true,
          "VALUETYPE": {
            "TYPE": "Integer"
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);

  t1.f3 = {};
  var status = true;

  // No se ha establecido f4, que es requerido, asi que debe lanzarse una excepcion.
  var testField = function (field, value, expectException) {
    var excpThrown = false;
    var result = true;
    try {
      t1[field] = value;
      t1.save();
    } catch (e) {
      excpThrown = true;
      result = expectException && e.path === "/f4" && e.code === Siviglia.model.BaseTypedException.ERR_REQUIRED_FIELD;
    }
    return result && expectException === excpThrown;
  }

  status = status && testField("f4", null, true);
  // Si le damos un valor vacio, deberia seguir dando una excepcion, porque
  // es equivalente a ser nulo.
  status = status && testField("f4", {}, true);
  // Se le asigna un valor correcto a t1.f4
  status = status && testField("f4", {aa: 11}, false);
  // Se asigna un valor con un null, y, como se permiten valores nulos, deberia seguir siendo correcto:
  status = status && testField("f4", {aa: null}, false);
  // Obtenemos el valor de f4, para estar seguro de que es lo que queremos.
  var curVal = t1.getPlainValue();
  status = status && curVal.f4.aa === null;
  // Comienzn las pruebas con el campo f3
  // f3 no va a dar una excepcion, porque aunque se le pasa un valor con una key nula,
  // y ALLOW_NULL_VALUES es false, al tener SET_ON_EMPTY=true, no da error.
  status = status && testField("f3", {aa: null}, false);
  // Comprobamos que, efectivamente, no existe ningun valor en f3
  curVal = t1.getPlainValue();
  var n = 0;
  for (var k in curVal.f3)
    n++;
  status = status && n == 0;

  t1.destruct();
  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})