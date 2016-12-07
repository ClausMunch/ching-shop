window.addEventListener("load", function () {

    class Colour {
        public red: number;
        public green: number;
        public blue: number;

        constructor(red: number, green: number, blue: number) {
            this.red = Colour.eightBit(red);
            this.green = Colour.eightBit(green);
            this.blue = Colour.eightBit(blue);
        }

        public static random(): Colour {
            return new Colour(
                Math.floor(Math.random() * 255),
                Math.floor(Math.random() * 255),
                Math.floor(Math.random() * 255),
            );
        }

        public static fromHex(hex: string): Colour {
            hex = hex.replace("#", "");
            return new Colour(
                parseInt(hex.slice(0, 2), 16),
                parseInt(hex.slice(2, 4), 16),
                parseInt(hex.slice(4, 6), 16),
            );
        }

        public static brand(): Colour {
            return new Colour(239, 69, 96);
        }

        public static suggestion(): Colour {
            return Colour.random().mix(Colour.brand()).pastel();
        }

        public pastel(): Colour {
            return this.mix(new Colour(255, 255, 255));
        }

        public mix(colour: Colour): Colour {
            return new Colour(
                (this.red + colour.red) / 2,
                (this.green + colour.green) / 2,
                (this.blue + colour.blue) / 2,
            );
        }

        public hexCode(): string {
            return "#"
                + this.red.toString(16)
                + this.green.toString(16)
                + this.blue.toString(16);
        }

        private static eightBit(value: number): number {
            return Math.floor(Math.min(255, Math.max(0, value)));
        }
    }

    function colourSuggestions(): HTMLDivElement {
        return [
            Colour.suggestion(),
            Colour.suggestion(),
            Colour.suggestion(),
            Colour.suggestion(),
            Colour.suggestion(),
            Colour.suggestion(),
            Colour.suggestion(),
        ].reduce(
            (suggestions: HTMLDivElement, colour: Colour) => {
                let bt: HTMLButtonElement = document.createElement("button");
                bt.type = "button";
                bt.setAttribute("data-colour", colour.hexCode());
                bt.innerText = colour.hexCode();
                bt.classList.add("btn", "btn-sm", "colour-suggestion");
                bt.style.cssText = `background:${colour.hexCode()};`;
                suggestions.appendChild(bt);
                suggestions.innerHTML = suggestions.innerHTML + "&nbsp;";

                return suggestions;
            },
            document.createElement("div")
        );
    }

    let colourField = <HTMLInputElement> document.getElementById("colour");

    function suggestColours() {
        $(".colour-suggestions")
            .html(colourSuggestions().innerHTML)
            .children(".colour-suggestion")
            .click(function () {
                colourField.value = this.innerText;
            });
    }

    suggestColours();
    $(".suggest-colours").on("click", suggestColours);
});
