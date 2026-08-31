import PropTypes from "prop-types";
import HtmlRenderer from "../../ui/HtmlRenderer";

AboutMe.propTypes = {
    aboutMe: PropTypes.object.isRequired,
    skills: PropTypes.array.isRequired,
};

function AboutMe({ aboutMe, skills }) {
    const { summary, portfolioImage } = aboutMe;
    const activeSkills = skills.filter((skill) => skill.isActive);
    const middleIndex = Math.ceil(activeSkills.length / 2);
    const skillColumns = [
        activeSkills.slice(0, middleIndex),
        activeSkills.slice(middleIndex),
    ];

    return (
        <section
            id="about"
            className="bg-secondary-light dark:bg-black text-black dark:text-white py-12 px-6"
        >
            <div className="max-w-6xl mx-auto flex flex-col md:flex-row items-start gap-10">
                <div className="flex-shrink-0">
                    <img
                        src={portfolioImage}
                        alt="Profile Image"
                        className="w-72 h-72 rounded-2xl object-cover grayscale hover:grayscale-0 transition-all duration-500"
                    />
                </div>

                <div className="flex-1">
                    <h2 className="text-3xl font-bold mb-6">About Me</h2>
                    <HtmlRenderer html={summary} />
                    <div className="flex flex-col md:flex-row gap-8">
                        {skillColumns.map((column, columnIndex) => (
                            <ul key={columnIndex} className="flex-1 space-y-4">
                                {column.map((skill) => (
                                    <li
                                        key={skill.id}
                                        className="dark:bg-light-blue bg-dark-blue dark:text-dark-blue text-light p-4 rounded-lg border-l-8 border-primary hover:translate-x-5 hover:border-secondary hover:dark:text-secondary-dark hover:text-lighter transition-all duration-300"
                                    >
                                        <h3 className="font-bold mb-1">
                                            {skill.title}
                                        </h3>

                                        <p className="text-sm">
                                            {skill.description}
                                        </p>
                                    </li>
                                ))}
                            </ul>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}

export default AboutMe;
