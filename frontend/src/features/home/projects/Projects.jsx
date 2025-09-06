import PropTypes from "prop-types";
import ProjectCard from "./ProjectCard";

Projects.propTypes = {
    projects: PropTypes.array.isRequired,
};

function Projects({ projects }) {
    return (
        <section
            id="projects"
            className="py-20 bg-dark-blue dark:bg-secondary-light text-light"
        >
            <div className="max-w-7xl mx-auto px-6">
                <h2 className="text-4xl font-bold text-center mb-16">
                    My Projects
                </h2>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {projects.map((project) => (
                        <ProjectCard
                            key={`${project.slug}-${project.id}`}
                            project={project}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
}

export default Projects;
