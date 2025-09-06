import { FaXmark } from "react-icons/fa6";
import PropTypes from "prop-types";
import NavbarItem from "./NavbarItem";

NavbarMobile.propTypes = {
    navbarItems: PropTypes.array.isRequired,
    isOpen: PropTypes.bool,
    onToggle: PropTypes.func,
};

function NavbarMobile({ navbarItems, isOpen, onToggle }) {
    return (
        <div
            className={`fixed top-0 right-0 ${
                !isOpen ? "translate-x-full" : "translate-x-0"
            } h-full w-64 bg-secondary-light dark:bg-secondary-dark transform transition-transform duration-300 ease-in-out z-60`}
        >
            <div className="flex flex-col items-start px-6 py-4">
                <button
                    onClick={onToggle}
                    className="mb-6 self-end text-2xl text-primary dark:text-white"
                >
                    <FaXmark className="text-2xl" />
                </button>
                <ul className="w-full divide-y divide-gray-300">
                    {navbarItems?.map((item) => (
                        <NavbarItem
                            to={item.link}
                            label={item.label}
                            onClick={onToggle}
                            key={`mobile-item-${item.id}`}
                            className="block my-2 text-lg text-primary dark:text-white"
                        />
                    ))}
                </ul>
            </div>
        </div>
    );
}

export default NavbarMobile;
