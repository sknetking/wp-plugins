jQuery(document).ready(function($){

    const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const container = document.getElementById('canvas-container');

let drawing = false;
let currentX = 0;
let currentY = 0;

const paths = [];
let redoStack = [];

// Default settings
let bgColor = '#ffffff';
let lineWidth = 2;
let lineColor = '#000000';

// Function to resize the canvas
function resizeCanvas() {
    canvas.width = container.clientWidth;
    canvas.height = container.clientHeight;
    redraw();
}

// Initialize canvas size
resizeCanvas();

// Update settings based on user input
document.getElementById('bgColor').addEventListener('input', (e) => {
    bgColor = e.target.value;
    canvas.style.backgroundColor = bgColor;
    redraw();
});

document.getElementById('lineWidth').addEventListener('input', (e) => {
    lineWidth = e.target.value;
    ctx.lineWidth = lineWidth;
});

document.getElementById('lineColor').addEventListener('input', (e) => {
     lineColor = e.target.value;
    ctx.strokeStyle = lineColor;
});
document.getElementById('clear').addEventListener('click', (e) => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
});
// Mouse Events
canvas.addEventListener('mousedown', (e) => {
    drawing = true;
    [currentX, currentY] = getCanvasCoordinates(e);
    ctx.beginPath();
    ctx.moveTo(currentX, currentY);
    paths.push([{ x: currentX, y: currentY, lineWidth, lineColor }]);
    redoStack = []; // Clear the redo stack on new action
});

canvas.addEventListener('mousemove', (e) => {
    if (!drawing) return;
    const [x, y] = getCanvasCoordinates(e);
    ctx.lineTo(x, y);
    ctx.stroke();
    paths[paths.length - 1].push({ x, y, lineWidth, lineColor });
});

canvas.addEventListener('mouseup', () => {
    drawing = false;
    ctx.closePath();
});

canvas.addEventListener('mouseleave', () => {
    drawing = false;
});

// Touch Events
canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    const touch = e.touches[0];
    drawing = true;
    [currentX, currentY] = getCanvasCoordinates(touch);
    ctx.beginPath();
    ctx.moveTo(currentX, currentY);
    paths.push([{ x: currentX, y: currentY, lineWidth, lineColor }]);
    redoStack = []; // Clear the redo stack on new action
});

canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    if (!drawing) return;
    const touch = e.touches[0];
    const [x, y] = getCanvasCoordinates(touch);
    ctx.lineTo(x, y);
    ctx.stroke();
    paths[paths.length - 1].push({ x, y, lineWidth, lineColor });
});

canvas.addEventListener('touchend', () => {
    drawing = false;
    ctx.closePath();
});

canvas.addEventListener('touchcancel', () => {
    drawing = false;
});

// Undo, Redo, and Save
document.getElementById('undo').addEventListener('click', () => {
    if (paths.length === 0) return;
    redoStack.push(paths.pop());
    redraw();
});

document.getElementById('redo').addEventListener('click', () => {
    if (redoStack.length === 0) return;
    paths.push(redoStack.pop());
    redraw();
});

function getCanvasCoordinates(event) {
    const rect = canvas.getBoundingClientRect();
    return [
        event.clientX - rect.left,
        event.clientY - rect.top
    ];
}

function redraw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = bgColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    paths.forEach(path => {
        if (path.length === 0) return;
        ctx.beginPath();
        ctx.moveTo(path[0].x, path[0].y);
        path.forEach(point => {
            ctx.lineWidth = point.lineWidth;
            ctx.strokeStyle = point.lineColor;
            ctx.lineTo(point.x, point.y);
            ctx.stroke();
        });
        ctx.closePath();
    });
}

// Handle window resize
window.addEventListener('resize', resizeCanvas);

});