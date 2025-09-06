import { useLoaderData } from "react-router-dom";
import ProjectShow from "../features/projects/ProjectShow";
import { getProjectBySlug } from "../services/apiProject";

function Project() {
    const project = useLoaderData();
    return <ProjectShow project={project} />;
}

export default Project;

// eslint-disable-next-line
export async function loader({ params }) {
    const project = await getProjectBySlug(params.projectSlug);
    return project;
}
