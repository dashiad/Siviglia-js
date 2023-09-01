(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "DateTime",
          "DEFAULT": "NOW"
        },
        "f4": {
          "TYPE": "DateTime",
          "STARTYEAR": 2000,
          "ENDYEAR": 2020
        },
        "f5": {
          "TYPE": "DateTime",
          "STRICTLYPAST": true
        },
        "f6": {
          "TYPE": "DateTime",
          "STRICTLYFUTURE": true
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  var convertDate = function (c) {
    var M = c.getMonth() + 1;
    var D = c.getDate();
    var H = c.getHours();
    var m = c.getMinutes();
    var s = c.getSeconds();
    M = (M < 10) ? ('0' + M) : M;
    D = (D < 10) ? ('0' + D) : D;
    H = (H < 10) ? ('0' + H) : H;
    m = (m < 10) ? ('0' + m) : m;
    s = (s < 10) ? ('0' + s) : s;
    return c.getFullYear() + '-' + M + '-' + D + ' ' + H + ':' + m + ':' + s;
  }
  var nChanges = 0;
  var nErrors = 0;
  for (var k = 3; k <= 6; k++) {
    t1["*f" + k].addListener("CHANGE", null, function () {
      nChanges++
    });
    t1["*f" + k].addListener("ERROR", null, function () {
      nErrors++
    });
  }

  var nExcp = 0;
  var timestamp1 = t1["*f3"].getDateValue().getTime();
  var timestamp2 = (new Date()).getTime();
  var status = (timestamp2 - timestamp1 < 1000);

  try {
    t1.f4 = "1999-12-30 00:00:00";
  } catch (e) {
    nExcp++;
  }
  status = status && (nErrors == 1 && nChanges == 0 && nExcp == 1);
  try {
    t1.f4 = "2021-01-01 00:00:00"
  } catch (e) {
    nExcp++;
  }
  status = status && (nErrors == 2 && nChanges == 0 && nExcp == 2);
  t1.f4 = "2010-01-01 00:00:00";
  status = status && (t1.f4 == "2010-01-01 00:00:00" && nChanges == 1 && nExcp == 2);


  try {
    t1.f5 = "2050-01-01 00:00:00";
  } catch (e) {
    nExcp++;
  }
  status = status && (nErrors == 3 && nExcp == 3);
  t1.f5 = "2010-01-01 00:00:00";
  status = status && (t1.f5 == "2010-01-01 00:00:00" && nChanges == 2 && nExcp == 3);

  try {
    t1.f6 = "2010-01-01 00:00:00";
  } catch (e) {
    nExcp++;
  }
  status = status && (nErrors == 4 && nExcp == 4);
  t1.f6 = "2050-01-01 00:00:00";
  status = status && (t1.f6 == "2050-01-01 00:00:00" && nChanges == 3 && nExcp == 4);


  var res = convertDate(t1["*f6"].getDateValue())
  status = status && (res == t1.f6);
  t1.destruct();
  return status;

//codeEnd
//callbackEnd
})