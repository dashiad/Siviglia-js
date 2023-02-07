Siviglia.require('stubs/mutualRequirement1.js', true, false).then(() => {
  signingBook.push('mutual requirement')
  console.log('running mutual requirement test')

  // requiredFunctionGamma()
})