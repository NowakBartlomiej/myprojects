let data = [
    {
        name: 'John',
        age: '30'
    },
    {
        name: 'Robert',
        age: '33'
    },
    {
        name: 'Marcus',
        age: '35'
    },
    {
        name: 'Zack',
        age: '38'
    },
    {
        name: 'Mathew',
        age: '41'
    },
    {
        name: 'Mark',
        age: '31'
    },
]

const info = document.getElementById('info');

let details = data.map((person) => {
    return (
        `<div>${[person.name]} is ${person.age} years old</div>`
    )
})

info.innerHTML = details.join('\n');

