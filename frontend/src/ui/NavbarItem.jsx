import { Link } from "react-router-dom";
import PropTypes from "prop-types";

NavbarItem.propTypes = {
  to: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  className: PropTypes.string,
  onClick: PropTypes.func,
};

function NavbarItem({ to, label, onClick, className }) {
  return (
    <li>
      <Link
        to={to}
        className={className}
        aria-label={`Navigate to ${label}`}
        onClick={onClick}
      >
        {label}
      </Link>
    </li>
  );
}

export default NavbarItem;
