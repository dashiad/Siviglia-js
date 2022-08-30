/* Poner el título del grupo de tests */
document.getElementsByTagName('title')[0].innerHTML = 'Batch test template'

var promiseList = []

// Añadir los tests a la lista de la siguiente forma:
promiseList.push(addTestPromise(
  "Nombre de test 1",
  "documentación 1",
  'testTemplate.html'
))
promiseList.push(addTestPromise(
  "Nombre de test 2",
  "documentación 2",
  'testTemplate.html'
))


//var mainPromise = $.Deferred()
$.when.apply($, promiseList).done(function () {
  checkTests()
});