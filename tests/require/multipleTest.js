var dependencyList = [
  'stubs/simpleDependency.js',
  'stubs/simpleDependency2.js',
  'stubs/simpleDependency3.js',
]

Siviglia.require(dependencyList, true, false).then(() => {
  signingBook.push('multipleTest')
  console.log('running multiple test')
})