import {JointElements} from "../jointElements"
import {Shape} from "../Shape"
/**
 * CallActivity class
 */
export default class extends Shape {
    constructor(options, graph, paper) {
        super(graph, paper)
        this.options = {
            id: null,
            type: "task",
            bounds: {
                x: null,
                y: null,
                width: 120,
                height: 80
            },
            incoming: null,
            outgoing: null,
            attributes: {}
        }
        this.config(options)
        this.configBounds(options.bounds)
    }

    /**
     * Render the SubProcess Based in options config
     */
    render() {
        this.shape = new JointElements.CallActivity({id: this.options.id})
        this.shape.position(this.options.bounds.x, this.options.bounds.y)
        this.shape.resize(this.options.bounds.width, this.options.bounds.height)
        this.shape.attr({
            label: {
                text: this.options.attributes.name
            }
        })
        this.shape.addTo(this.graph)
    }
}
