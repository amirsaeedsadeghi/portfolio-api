import { HiChevronDown } from "react-icons/hi2";
import PropTypes from "prop-types";

Hero.propTypes = {
    aboutMe: PropTypes.string.isRequired,
};

function Hero({ aboutMe }) {
    const { title } = aboutMe;
    return (
        <section
            id="hero"
            className="hero relative flex flex-col justify-center items-center min-h-screen text-center bg-cover bg-center text-white px-4"
        >
            <div className="overflow-hidden">
                <h1 className="typewriter text-xl sm:text-2xl md:text-6xl font-bold">
                    {title}
                </h1>
            </div>

            <div className="absolute bottom-12">
                <HiChevronDown className="text-4xl text-white bounce-slow" />
            </div>
        </section>
    );
}

export default Hero;
