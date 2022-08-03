const catDiv = document.getElementById('catResult')
const dogDiv = document.getElementById('dogResult')

const catBtn = document.getElementById('catBtn')
const dogBtn = document.getElementById('dogBtn')

catBtn.addEventListener('click', () => {
    fetch('https://aws.random.cat/meow')
    .then(result => result.json())
    .then(data => {
        let image = data.file
        catDiv.innerHTML = `<img src="${image}" alt="cat"/>`
    })
})

dogBtn.addEventListener('click', () => {
    fetch('https://dog.ceo/api/breeds/image/random') 
    .then(result => result.json())
    .then(data => {
        let image = data.message
        dogDiv.innerHTML = `<img src="${image}" alt="dog"/>`
    })
})