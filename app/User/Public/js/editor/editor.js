class Picture {
    constructor(width, height, pixels) {
        this.width = width;
        this.height = height;
        this.pixels = pixels;
    }
    static empty(width, height, color) {
        let pixels = new Array(width * height).fill(color);
        return new Picture(width, height, pixels);
    }
    pixel(x, y) {
        return this.pixels[x + y * this.width];
    }
    draw(pixels) {
        let copy = this.pixels.slice();
        for (let {x, y, color} of pixels) {
            copy[x + y * this.width] = color;
        }
        return new Picture(this.width, this.height, copy);
    }
}

function elt(type, props, ...children) {
    let dom = document.createElement(type);
    if (props) Object.assign(dom, props);
    for (let child of children) {
        if (typeof child != "string") dom.appendChild(child);
        else dom.appendChild(document.createTextNode(child));
    }
    return dom;
}

let scale = 10;

class PictureCanvas {
    constructor(picture, pointerDown) {
        this.dom = elt("canvas", {
            onmousedown: event => this.mouse(event, pointerDown),
            ontouchstart: event => this.touch(event, pointerDown)
        });
        this.syncState(picture);
    }

    syncState(picture) {
        if (this.picture === picture) return;
        this.picture = picture;
        drawPicture(this.picture, this.dom, scale);
    }

    mouse(downEvent, onDown) {
        if (downEvent.button !== 0) return;
        let pos = pointerPosition(downEvent, this.dom);
        let onMove = onDown(pos);
        if (!onMove) return;

        let move = moveEvent => {
            if (moveEvent.buttons === 0) {
                this.dom.removeEventListener("mousemove", move);
            } else {
                let newPos = pointerPosition(moveEvent, this.dom);
                if (newPos.x === pos.x && newPos.y === pos.y) return;
                pos = newPos;
                onMove(newPos);
            }
        };
        this.dom.addEventListener("mousemove", move);
    };

    touch(startEvent, onDown) {
        let pos = pointerPosition(startEvent.touches[0], this.dom);
        let onMove = onDown(pos);
        startEvent.preventDefault();
        if (!onMove) return;
        let move = (moveEvent) => {
            let newPos = pointerPosition(moveEvent.touches[0],
                this.dom);
            if (newPos.x === pos.x && newPos.y === pos.y) return;
            pos = newPos;
            onMove(newPos);
        };
        let end = () => {
            this.dom.removeEventListener("touchmove", move);
            this.dom.removeEventListener("touchend", end);
        };
        this.dom.addEventListener("touchmove", move);
        this.dom.addEventListener("touchend", end);
    };
}

function drawPicture(picture, canvas, scale) {
    canvas.width = picture.width * scale;
    canvas.height = picture.height * scale;
    let cx = canvas.getContext("2d");

    for (let y = 0; y < picture.height; y++) {
        for (let x = 0; x < picture.width; x++) {
            cx.fillStyle = picture.pixel(x, y);
            cx.fillRect(x * scale, y * scale, scale, scale);
        }
    }
}

function pointerPosition(pos, domNode) {
    let rect = domNode.getBoundingClientRect();
    return {x: Math.floor((pos.clientX - rect.left) / scale),
        y: Math.floor((pos.clientY - rect.top) / scale)};
}

class PixelEditor {
    constructor(state, config) {
        let {tools, controls, dispatch} = config;
        this.state = state;

        this.canvas = new PictureCanvas(state.picture, pos => {
            let tool = tools[this.state.tool];
            let onMove = tool(pos, this.state, dispatch);
            if (onMove) return pos => onMove(pos, this.state);
        });
        this.controls = controls.map(Control => new Control(state, config));
        this.dom = elt("div", {}, this.canvas.dom,
            ...this.controls.reduce(
                (a, c) => a.concat(" ", c.dom), []));
    }

    syncState(state) {
        this.state = state;
        this.canvas.syncState(state.picture);
        for (let ctrl of this.controls) ctrl.syncState(state);
    }
}

class ToolSelect {
    constructor(state , {tools , dispatch}) {
        this.select = [];
        for (let tool in tools) {
            this.select.push(elt("div", {
                    classList: 'tool-button icon ' + tool + '-icon tooltip' ,
                    value: tool ,
                    onclick: function () {
                        dispatch({tool: this.value})
                        console.log(this.value);
                    }
                }
            ));
        }
        this.dom = this.select;
    }

    syncState(state) {
        this.select.value = state.tool;
    }
}

class ColorSelect {
    constructor(state, {dispatch}) {
        this.input = elt("input", {
            type: "color",
            value: state.color,
            classList: "color-tool",
            onchange: () => dispatch({color: this.input.value})
        });
        this.dom = this.input;
    }
    syncState(state) { this.input.value = state.color; }
}

function draw(pos, state, dispatch) {
    function drawPixel({x, y}, state) {
        let drawn = {x, y, color: state.color};
        dispatch({picture: state.picture.draw([drawn])});
    }
    drawPixel(pos, state);
    return drawPixel;
}

function rectangle(start, state, dispatch) {
    function drawRectangle(pos) {
        let xStart = Math.min(start.x, pos.x);
        let yStart = Math.min(start.y, pos.y);
        let xEnd = Math.max(start.x, pos.x);
        let yEnd = Math.max(start.y, pos.y);
        let drawn = [];
        for (let y = yStart; y <= yEnd; y++) {
            for (let x = xStart; x <= xEnd; x++) {
                drawn.push({x, y, color: state.color});
            }
        }
        dispatch({picture: state.picture.draw(drawn)});
    }
    drawRectangle(start);
    return drawRectangle;
}

function fill({x, y}, state, dispatch) {
    let around = [{dx: -1, dy: 0}, {dx: 1, dy: 0},
        {dx: 0, dy: -1}, {dx: 0, dy: 1}];
    let targetColor = state.picture.pixel(x, y);
    let drawn = [{x, y, color: state.color}];
    for (let done = 0; done < drawn.length; done++) {
        for (let {dx, dy} of around) {
            let x = drawn[done].x + dx, y = drawn[done].y + dy;
            if (x >= 0 && x < state.picture.width &&
                y >= 0 && y < state.picture.height &&
                state.picture.pixel(x, y) === targetColor &&
                !drawn.some(p => p.x === x && p.y === y)) {
                drawn.push({x, y, color: state.color});
            }
        }
    }
    dispatch({picture: state.picture.draw(drawn)});
}

function pick(pos, state, dispatch) {
    dispatch({color: state.picture.pixel(pos.x, pos.y)});
}

class SaveButton {
    constructor(state) {
        this.picture = state.picture;
        this.dom = elt("div", {
            classList: 'tool-button icon save-icon tooltip',
            onclick: () => this.save()
        });
    }
    save() {
        let canvas = elt("canvas");
        drawPicture(this.picture, canvas, scale);
        let link = elt("a", {
            href: canvas.toDataURL(),
            download: this.generateFileName()
        });
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    /**
     * UUID v.4 for generating filenames
     *
     * @returns {string}
     */
    generateFileName() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx.png'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    syncState(state) { this.picture = state.picture; }
}

class LoadButton {
    constructor(state, {dispatch}) {
        this.picture = state.picture;
        this.dom = elt("div", {
            classList: 'tool-button icon load-icon tooltip',
            onclick: () => this.startLoad({dispatch})
        });
    }
    startLoad({dispatch}) {
        let form = new FormData();
        form.append("picture", JSON.stringify(this.picture))
        fetch('http://localhost:8080/pixel-editor/save', {
            method: 'POST',
            body: form,
        })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                dispatch({
                    picture: new Picture(data.message.width, data.message.height, data.message.pixels)
                });
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
    syncState(state) { this.picture = state.picture; }
}

function finishLoad(file, dispatch) {
    if (file == null) return;
    let reader = new FileReader();
    reader.addEventListener("load", () => {
        let image = elt("img", {
            onload: () => dispatch({
                picture: pictureFromImage(image)
            }),
            src: reader.result
        });
    });
    reader.readAsDataURL(file);
}

/* TODO: finish this / scale problem */
function pictureFromImage(image) {
    let widthImage = Math.min(100, image.width - 400);
    let heightImage = Math.min(100, image.height - 400);
    let widthCanvas = widthImage/scale;
    let heightCanvas = heightImage/scale;
    let canvas = elt("canvas", {widthCanvas, heightCanvas});
    let cx = canvas.getContext("2d");
    cx.drawImage(image, 0, 0);
    let pixels = [];
    let {data} = cx.getImageData(0, 0, widthImage, heightImage);

    function hex(n) {
        return n.toString(16).padStart(2, "0");
    }
    for (let i = 0; i < data.length; i += 4) {
        let [r, g, b] = data.slice(i, i + 3);
        pixels.push("#" + hex(r) + hex(g) + hex(b));
    }
    return new Picture(widthCanvas, heightCanvas, pixels);
}

function historyUpdateState(state, action) {
    if (action.undo === true) {
        if (state.done.length === 0) return state;
        return Object.assign({}, state, {
            picture: state.done[0],
            done: state.done.slice(1),
            doneAt: 0
        });
    } else if (action.picture &&
        state.doneAt < Date.now() - 1000) {
        return Object.assign({}, state, action, {
            done: [state.picture, ...state.done],
            doneAt: Date.now()
        });
    } else {
        return Object.assign({}, state, action);
    }
}

class UndoButton {
    constructor(state, {dispatch}) {
        this.dom = elt("div", {
            classList: 'tool-button icon undo-icon tooltip',
            onclick: () => dispatch({undo: true}),
            disabled: state.done.length === 0
        });
    }
    syncState(state) {
        this.dom.disabled = state.done.length === 0;
    }
}

let startState = {
    tool: "draw",
    color: "#000000",
    picture: Picture.empty(pictureSizeWidth(), pictureSizeHeight(), "#f0f0f0"),
    done: [],
    doneAt: 0
};

let baseTools = {draw, fill, rectangle, pick};

let baseControls = [
    ColorSelect, ToolSelect, SaveButton, LoadButton, UndoButton
];

function pictureSizeWidth() {
    let pictureWidth = Math.floor(window.screen.availWidth/scale);
    if (window.screen.availWidth > 600) {
        pictureWidth = 600/scale;
    }
    return pictureWidth;
}

function pictureSizeHeight() {
    let pictureHeight = Math.floor(window.screen.availHeight/scale);
    if (window.screen.availWidth > 600) {
        pictureHeight = 600/scale;
    }
    return pictureHeight;
}

const tooltips = [
    'Drawing a pixel',
    'Fill area',
    'Drawing a rectangle',
    'Pick a color',
    'Save as .png',
    'Save on server',
    'Undo',
]

function eltTooltips(app) {
    const buttons = app.dom.querySelectorAll(':not(canvas)');
    buttons.forEach((button, index) => {
        button.appendChild(elt('span', {
            classList: 'tooltiptext',
            innerHTML: tooltips[index-2]
        }));
    });
}

function startPixelEditor({state = startState,
                              tools = baseTools,
                              controls = baseControls}) {
    let app = new PixelEditor(state, {
        tools,
        controls,
        dispatch(action) {
            state = historyUpdateState(state, action);
            app.syncState(state);
        }
    });
    app.dom.appendChild(elt('div', {
        classList: 'tool-button-collection',
    }, ...app.dom.querySelectorAll(':not(canvas)')));

    eltTooltips(app);

    return app.dom;
}
  