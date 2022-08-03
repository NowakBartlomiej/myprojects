const wrapper = document.querySelector('.wrapper')
const header = wrapper.querySelector('header')

const onDrag = ({movementX, movementY}) => {
    let getStyle = window.getComputedStyle(wrapper) // Getting all styles of wrapper element
    let left = parseInt(getStyle.left)
    let top = parseInt( getStyle.top )
    
    wrapper.style.left = `${left + movementX}px`
    wrapper.style.top = `${top + movementY}px`
    
}

header.addEventListener('mousedown', () => {
    header.classList.add('active')
    window.addEventListener('mousemove', onDrag)
})

document.addEventListener('mouseup', () => {
    header.classList.remove('active')
    window.removeEventListener('mousemove', onDrag)
})