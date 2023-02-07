Siviglia.require('stubs/thirdDependencyFromLast.js', true, false).then(() => {
  signingBook.push('chainTest')
  console.log('running chain test')

  lastFunction()
})