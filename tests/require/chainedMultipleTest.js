var dependencyList = [
  'stubs/lastDependency.js',
  'stubs/lastButOneDependency.js',
  'stubs/thirdDependencyFromLast.js'
]

Siviglia.require(dependencyList, true, false).then(() => {
  signingBook.push('chainedMultipleTest')
  console.log('running chained multiple test')

  lastFunction()
})