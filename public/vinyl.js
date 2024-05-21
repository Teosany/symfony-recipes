export default class {
    constructor(title, year) {
        this.title = title;
        this.year = year;
    }
    describe() {
        return `${this.title} was released in ${this.year}`;
    }
}
