import PropTypes from "prop-types";

TechStacks.propTypes = {
    stacks: PropTypes.array.isRequired,
};

function TechStacks({ stacks }) {
    return (
        <ul className="techstacks flex justify-start gap-x-1 mt-8">
            {stacks.map((stack) => (
                <li key={stack.image}>
                    <img
                        className="bg-sky-50 w-8 h-8 object-cover p-1 rounded-full border border-dark-blue dark:border-white"
                        src={stack.image}
                        alt={stack.alt}
                    />
                </li>
            ))}
        </ul>
    );
}

export default TechStacks;
