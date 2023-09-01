(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "TypeSwitcher",
            "TYPE_FIELD": "TYPE",
            "ALLOWED_TYPES": {
              "T1": {
                "TYPE": "Container",
                "FIELDS": {
                  "TYPE": {
                    "TYPE": "String",
                    "FIXED": "T1"
                  },
                  "CAMPO": {
                    "TYPE": "String"
                  }
                }
              },
              "T2": {
                "TYPE": "Container",
                "FIELDS": {
                  "TYPE": {
                    "TYPE": "String",
                    "FIXED": "T2"
                  },
                  "CAMPO2": {
                    "TYPE": "String"
                  }
                }
              },
              "T3": {
                "TYPE": "Container",
                "FIELDS": {
                  "TYPE": {
                    "TYPE": "String",
                    "FIXED": "T3"
                  },
                  "CAMPO3": {
                    "TYPE": "String"
                  }
                }
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  var f = t1["*f3"];
  var nErrors = 0;
  var nChanges = 0;

  f.addListener("CHANGE", null, function () {
    nChanges++
  });
  var a1 = {"TYPE": "T1", "CAMPO": "abcde"};
  var a2 = {"TYPE": "T2"};
  var a3 = {"TYPE": "T3", "CAMPO3": "jklm"};
  f.setValue([a1, a2, a3]);

  f.splice(1, 1);

  var status = f[0].CAMPO === "abcde" && f[1].CAMPO3 === "jklm" && f.length === 2;

  // El splice ha movido el elemento que antes estaba en 2, a la posicion 1. Para que esto funcione, ha
  // habido que crear un nuevo typeswitcher en 1, con el valor del typeswitcher que habia en 2.

  // Lo importante, es que ese valor siga apuntando al mismo objeto, de forma que sigue siendo el mismo
  // valor referenciado por a3. Asi que si cambiamos a3, lo que hay en f[1] tiene que cambiar tambien.
  // NOTA: LO ANTERIOR NO ES POSIBLE: EL TYPESWITCHER QUE HABIA EN 2, EVENTIZO EL OBJETO a3 PARA QUE APUNTARA A ÉL.
  // SI LUEGO ESE OBJETO PASA A SER EL VALOR DEL TYPESWITCHER QUE SE CREA EN 1, SOLO HABRIA 2 POSIBILIDADES:
  // 1) RE-EVENTIZAR a3 PARA QUE AHORA APUNTE AL TYPESWITCHER NUEVO. ESTO NO ES POSIBLE, PORQUE NADIE GARANTIZA
  // QUE EL TYPESWITCHER QUE HAY EN 2 (Y QUE LO EVENTIZÓ INICIALMENTE), VAYA A DESAPARECER: LO SIGUE USANDO. SERIA
  // UN VALOR COMPARTIDO
  // 2) NO RE-EVENTIZAR : a3 SEGUIRIA APUNTANDO A TYPESWITCHER 2, QUE VA A SER DESTRUIDO.
  // ASI QUE NO QUEDA MAS REMEDIO QUE HACER UNA COPIA!


  // a3.CAMPO3="JORGITO";
  a3 = f[1];
  a3.CAMPO3 = "JORGITO";


  status = status && f[1].CAMPO3 === "JORGITO";

  t1.destruct();

  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})