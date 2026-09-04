import PropTypes from "prop-types";
import { useLayoutEffect, useRef, useState } from "react";
import { HiChevronDown } from "react-icons/hi2";

Hero.propTypes = {
    aboutMe: PropTypes.object.isRequired,
};

function Hero({ aboutMe }) {
    const { title } = aboutMe;

    const titleRef = useRef(null);
    const [titleWidth, setTitleWidth] = useState(0);

    useLayoutEffect(() => {
        const titleElement = titleRef.current;
    
        if (!titleElement) {
            return;
        }
    
        const updateTitleWidth = () => {
            setTitleWidth(
                titleElement.getBoundingClientRect().width
            );
        };
    
        updateTitleWidth();
    
        window.addEventListener("resize", updateTitleWidth);
    
        return () => {
            window.removeEventListener("resize", updateTitleWidth);
        };
    }, [title]);

    return (
        <section
            id="hero"
            className="hero relative flex min-h-screen flex-col items-center justify-center bg-cover bg-center px-4 text-center text-white"
        >
            <div className="max-w-full overflow-hidden">
                <h1
                    className="typewriter text-xl font-bold sm:text-2xl md:text-6xl"
                    style={{
                        "--title-width": `${titleWidth+8}px`,
                        "--typing-steps": title.length,
                    }}
                >
                    <span ref={titleRef}>{title}</span>
                </h1>
            </div>

            <div className="absolute bottom-12">
                <HiChevronDown className="bounce-slow text-4xl text-white" />
            </div>
        </section>
    );
}

export default Hero;
