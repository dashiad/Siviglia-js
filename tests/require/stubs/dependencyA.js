Siviglia.require(['/packages/Siviglia/tests/require/stubs/dependencyB.js'], true, false).then(function () {
  signingBook.push('A')
  console.log('Ejecución de dependencia A')

  // functionOnB ()
})