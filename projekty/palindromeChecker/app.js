const txtInput = document.querySelector('.inputs input')
const checkButton = document.querySelector('.inputs button')
const infoTxt = document.querySelector('.info-txt')

let filterInput

checkButton.addEventListener('click', () => {
    let reverseInput = filterInput.split('').reverse().join('')
    infoTxt.style.display = 'block'

    if(filterInput != reverseInput) {
        return infoTxt.innerHTML = `No, <span>'${filterInput}'</span> is not a palindrome`
    }
    infoTxt.innerHTML = `Yes, <span>'${filterInput}'</span> is a palindrome`
    
    
})


txtInput.addEventListener('keyup', () => {
    filterInput = txtInput.value.toLowerCase().replace(/[^A-Z0-9]/ig, '')
    if (filterInput) {
        return checkButton.classList.add('active')
    }
    infoTxt.style.display = 'none'
    checkButton.classList.remove('active')
})