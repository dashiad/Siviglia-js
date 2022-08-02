Siviglia.require(['/packages/Siviglia/tests/require/stubs/dependencyB.js'], true, false).then(function () {
  signingBook.push('Soy A')
  console.log('Ejecución de depencencia A')

  // functionOnB ()
})