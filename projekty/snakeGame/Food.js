import { onSnake, expendSnake } from './Snake.js'
import { randomGridPosition } from './grid.js'

let food = getRandomFoodPosition()
const EXPANSION_RATE = 1

export const update = () => {
    if (onSnake(food)) {
        expendSnake(EXPANSION_RATE)
        food = getRandomFoodPosition()
    }
}

export const draw = (gameBoard) => {
    
    const foodElement = document.createElement('div')
    foodElement.style.gridRowStart = food.y
    foodElement.style.gridColumnStart = food.x
    foodElement.classList.add('food')
    gameBoard.appendChild(foodElement)   
}

function getRandomFoodPosition() {
    let newFoodPosition
    while (newFoodPosition == null || onSnake(newFoodPosition)) {
        newFoodPosition = randomGridPosition()
    }
    return newFoodPosition
}
