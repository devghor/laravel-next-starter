import { QueryKeys } from '@/constants/query-keys';
import {
  createRole,
  deleteRole,
  getRoles,
  updateRole
} from '@/services/uam/role';
import { useMutation, useQuery } from '@tanstack/react-query';

export const useRoles = () => {
  return useQuery({
    queryKey: QueryKeys.UAM_ROLES.GET_ALL,
    queryFn: getRoles
  });
};

export const useCreateRole = () => {
  return useMutation({ mutationFn: createRole });
};

export const useUpdateRole = () => {
  return useMutation({ mutationFn: updateRole });
};

export const useDeleteRole = () => {
  return useMutation({ mutationFn: deleteRole });
};
