


Siviglia.require('stubs/mutualRequirement1.js', true, false).then(() => {
  signingBook.push('mutual requirement B')
  console.log('running mutual requirement B')

  function requiredFunctionBeta () {
    console.log('I am function Beta')
    requiredFunctionAlpha()
  }
})