import React from "react";
import { useLocation, useNavigate } from "react-router-dom";
import {
    AssignmentInd as AssignedIcon,
    Dashboard as DashboardIcon,
    Inventory2 as OrdersIcon,
    Payments as PaymentsIcon,
    Person as ProfileIcon,
} from "@mui/icons-material";
import { BottomNavigation, BottomNavigationAction, Paper } from "@mui/material";

export interface BottomNavProps {
    value?: number;
    onChange?: (event: React.SyntheticEvent, newValue: number) => void;
}

export const BottomNav: React.FC<BottomNavProps> = ({ value, onChange }) => {
    const navigate = useNavigate();
    const location = useLocation();

    // Compute active tab from current URL route if value prop is not passed explicitly
    const getActiveTab = () => {
        if (value !== undefined) return value;
        const path = location.pathname;
        if (path.includes("/orders")) return 1;
        if (path.includes("/assigned")) return 2;
        if (path.includes("/payments")) return 3;
        if (path.includes("/profile/edit")) return 4;
        return 0; // default to Dashboard
    };

    const handleTabChange = (event: React.SyntheticEvent, newValue: number) => {
        if (onChange) {
            onChange(event, newValue);
        }

        switch (newValue) {
            case 0:
                navigate("/dashboard");
                break;
            case 1:
                navigate("/orders");
                break;
            case 2:
                navigate("/assigned");
                break;
            case 3:
                navigate("/payments");
                break;
            case 4:
                navigate("/profile/edit");
                break;
            default:
                navigate("/dashboard");
        }
    };

    return (
        <Paper
            elevation={12}
            sx={{
                position: "fixed",
                bottom: 0,
                left: 0,
                right: 0,
                zIndex: 1300,
                background: "rgba(30, 41, 59, 0.95)",
                backdropFilter: "blur(16px)",
                borderTop: "1px solid rgba(255, 255, 255, 0.1)",
            }}
        >
            <BottomNavigation
                showLabels
                value={getActiveTab()}
                onChange={handleTabChange}
                sx={{
                    bgcolor: "transparent",
                    height: 52,
                    "& .MuiBottomNavigationAction-root": {
                        color: "#94a3b8",
                        minWidth: 0,
                        padding: "4px 2px",
                        "& .MuiSvgIcon-root": {
                            fontSize: 20,
                            mb: 0.2,
                        },
                        "& .MuiBottomNavigationAction-label": {
                            fontSize: "0.65rem",
                            lineHeight: 1.1,
                            whiteSpace: "nowrap",
                            "&.Mui-selected": {
                                fontSize: "0.7rem",
                                fontWeight: 700,
                            },
                        },
                        "&.Mui-selected": {
                            color: "#6366f1",
                        },
                    },
                }}
            >
                <BottomNavigationAction
                    label="Dashboard"
                    icon={<DashboardIcon />}
                />
                <BottomNavigationAction
                    label="My Orders"
                    icon={<OrdersIcon />}
                />
                <BottomNavigationAction
                    label="Assigned"
                    icon={<AssignedIcon />}
                />
                <BottomNavigationAction
                    label="Payments"
                    icon={<PaymentsIcon />}
                />
                <BottomNavigationAction
                    label="Profile"
                    icon={<ProfileIcon />}
                />
            </BottomNavigation>
        </Paper>
    );
};

export default BottomNav;
